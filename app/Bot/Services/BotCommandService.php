<?php

namespace App\Bot\Services;

use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Data\OutgoingBotMessage;
use App\Enums\MessageToUser;
use App\Enums\UserRoleEnum;
use App\Models\BotSubscription;
use App\Models\User;
use App\Models\UserMessengerAccount;
use App\Services\BotInviteService;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Cache;

class BotCommandService
{
    public function __construct(
        protected BotPayloadService $payloadService,
        protected BotRegistry $botRegistry,
        protected BotReviewFormatter $botReviewFormatter,
    ) {}

    public function handle(BotDefinition $bot, IncomingBotUpdate $update): void
    {
        if ($update->inviteToken !== null) {
            $this->subscribe($bot, $update);

            return;
        }

        if ($update->callbackPayload !== null) {
            $this->handleCallback($bot, $update);

            return;
        }

        if ($this->handlePendingInput($bot, $update)) {
            return;
        }

        $command = $this->normalizeCommand($update->text);

        match (true) {
            $command === '/test' || $command === 'test' => $this->reply($bot, $update->recipientId, 'Я работаю!'),
            $command === '/start' || $command === 'start' => $this->subscribe($bot, $update),
            $command === '/help' || $command === 'help' => $this->reply($bot, $update->recipientId, $this->helpText()),
            $command === '/reviews' || $command === 'reviews' => $this->sendReviewsLink($bot, $update),
            str_starts_with($command, '/say_allaud') || str_starts_with($command, 'say_allaud') => $this->sayAllaud($bot, $update),
            default => null,
        };
    }

    protected function subscribe(BotDefinition $bot, IncomingBotUpdate $update): void
    {
        if ($update->inviteToken !== null) {
            try {
                app(BotInviteService::class)->claim($update->inviteToken, $update);
                $this->reply($bot, $update->recipientId, $this->helpText());

                return;
            } catch (\Throwable $exception) {
                $this->reply($bot, $update->recipientId, $exception->getMessage());

                return;
            }
        }

        $user = match ($bot->driver) {
            'telegram' => $this->resolveTelegramUser($update),
            'vk' => User::byVkUserId((string) $update->senderId)->first(),
            default => null,
        };

        if (! $user instanceof User) {
            $this->reply($bot, $update->recipientId, MessageToUser::UserNotRegister->value);

            return;
        }

        BotSubscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'driver' => $bot->driver,
                'bot' => $bot->name,
            ],
            [
                'recipient_id' => $update->recipientId,
                'subscribed_at' => now(),
            ],
        );

        UserMessengerAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'driver' => $bot->driver,
            ],
            [
                'username' => $bot->driver === 'telegram' ? $update->senderUsername : null,
                'external_id' => $bot->driver === 'vk' ? (string) $update->senderId : $update->recipientId,
            ],
        );

        $this->reply($bot, $update->recipientId, $this->helpText());
    }

    protected function sendReviewsLink(BotDefinition $bot, IncomingBotUpdate $update): void
    {
        $user = $this->resolveSubscribedUser($bot, $update->recipientId);

        if (! $user instanceof User) {
            $this->reply($bot, $update->recipientId, MessageToUser::UserNotRegister->value);

            return;
        }

        $token = app(ReviewService::class)->getUrlToken();
        $url = rtrim((string) config('app.url'), '/')."/{$token}/reviews";

        $this->reply($bot, $update->recipientId, "Ссылка:\n{$url}");
    }

    protected function sayAllaud(BotDefinition $bot, IncomingBotUpdate $update): void
    {
        $user = $this->resolveSubscribedUser($bot, $update->recipientId);

        if (! $user instanceof User) {
            $this->reply($bot, $update->recipientId, MessageToUser::UserNotRegister->value);

            return;
        }

        if (! in_array($user->role->name, [UserRoleEnum::Founder->name, UserRoleEnum::Ssm->name], true)) {
            $this->reply($bot, $update->recipientId, 'Нет разрешения на данное действие!');

            return;
        }

        $text = trim((string) preg_replace('/^\/?say_allaud\b/u', '', (string) $update->text));

        if ($text === '') {
            $this->reply($bot, $update->recipientId, 'Отсутствует текст сообщения!');

            return;
        }

        BotSubscription::query()
            ->where('driver', $bot->driver)
            ->where('bot', $bot->name)
            ->where('user_id', '!=', $user->id)
            ->whereHas('user', fn ($query) => $query->where('role', UserRoleEnum::Control->name))
            ->get()
            ->each(fn (BotSubscription $subscription) => $this->reply($bot, $subscription->recipient_id, $text));

        $this->reply($bot, $update->recipientId, 'Сообщение отправлено');
    }

    protected function handleCallback(BotDefinition $bot, IncomingBotUpdate $update): void
    {
        $data = $this->payloadService->parse($update->callbackPayload);
        $action = $data['action'] ?? null;

        match ($action) {
            'handle_work_start' => app(BotInteractionService::class)->markReviewNeedWork($bot, $update, (int) $data['review_id']),
            'handle_no_work_required' => app(BotInteractionService::class)->markReviewNoWorkRequired($bot, $update, (int) $data['review_id']),
            'handle_report_insert' => app(BotInteractionService::class)->requestReportInput($bot, $update, (int) $data['review_id'], $data['fill']),
            'handle_hide_buttons' => app(BotInteractionService::class)->hideButtons($bot, $update),
            default => null,
        };
    }

    protected function handlePendingInput(BotDefinition $bot, IncomingBotUpdate $update): bool
    {
        $cacheKey = $this->pendingInputKey($bot, $update->recipientId);
        $payload = Cache::get($cacheKey);

        if (! is_array($payload) || ($update->text === null || trim($update->text) === '')) {
            return false;
        }

        Cache::forget($cacheKey);

        app(BotInteractionService::class)->saveReport($bot, $update, (int) $payload['review_id'], $payload['fill'], $payload['message_id'] ?? null);

        return true;
    }

    public function pendingInputKey(BotDefinition $bot, string $recipientId): string
    {
        return "bot-pending-input:{$bot->driver}:{$bot->name}:{$recipientId}";
    }

    protected function reply(BotDefinition $bot, string $recipientId, string $text): void
    {
        $this->botRegistry->driver($bot)->sendMessage($bot, new OutgoingBotMessage(
            recipientId: $recipientId,
            text: $text,
        ));
    }

    protected function resolveSubscribedUser(BotDefinition $bot, string $recipientId): ?User
    {
        return BotSubscription::query()
            ->where('driver', $bot->driver)
            ->where('bot', $bot->name)
            ->where('recipient_id', $recipientId)
            ->first()
            ?->user;
    }

    protected function resolveTelegramUser(IncomingBotUpdate $update): ?User
    {
        $userByExternalId = UserMessengerAccount::query()
            ->where('driver', 'telegram')
            ->where('external_id', $update->recipientId)
            ->first();

        if ($userByExternalId instanceof UserMessengerAccount) {
            return $userByExternalId->user;
        }

        if ($update->senderUsername === null) {
            return null;
        }

        return User::byTgUsername($update->senderUsername)->first();
    }

    protected function normalizeCommand(?string $text): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        $command = explode(' ', $text)[0];

        return strtolower((string) preg_replace('/@.+$/', '', $command));
    }

    protected function helpText(): string
    {
        return <<<'TEXT'
Всем привет!
🧩 Это чат-бот с отзывами с 2ГИС, Яндекс и других платформ.

🎯 Цель бота - быстрое реагирование на отзывы и фиксация действий по ним.

1. Каждому управляющему приходят отзывы только по его точкам.
2. Можно принять отзыв в работу или отметить, что меры не требуются.
3. Если отзыв взят в работу, управляющий оставляет комментарий, после чего SMM готовит ответ гостю.

По всем вопросам @oriaplanet
TEXT;
    }
}
