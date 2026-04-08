<?php

namespace App\Bot\Services;

use App\Bot\Data\BotButton;
use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Data\OutgoingBotMessage;
use App\Enums\MessageToUser;
use App\Enums\UserRoleEnum;
use App\Models\BotMessage;
use App\Models\Review;
use DateTime;
use Illuminate\Support\Facades\Cache;

class BotInteractionService
{
    public function __construct(
        protected BotPayloadService $payloadService,
        protected BotNotificationService $botNotificationService,
        protected BotRegistry $botRegistry,
        protected BotReviewFormatter $botReviewFormatter,
    ) {}

    public function markReviewNeedWork(BotDefinition $bot, IncomingBotUpdate $update, int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);
        $review->updateOrFail([
            'start_work_on' => new DateTime,
        ]);

        $this->syncCallbackMessageId($bot, $update, $reviewId);
        $this->botRegistry->driver($bot)->answerCallback($bot, $update, 'Принято!');
        $this->refreshReviewMessages($reviewId);
    }

    public function markReviewNoWorkRequired(BotDefinition $bot, IncomingBotUpdate $update, int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);
        $dateTime = new DateTime;

        $review->updateOrFail([
            'start_work_on' => $dateTime,
            'end_work_on' => $dateTime,
            'control_review' => MessageToUser::NoWorkNeeded->value,
        ]);

        $this->syncCallbackMessageId($bot, $update, $reviewId);
        $this->botRegistry->driver($bot)->answerCallback($bot, $update, 'Принято!');
        $this->refreshReviewMessages($reviewId);
    }

    public function requestReportInput(BotDefinition $bot, IncomingBotUpdate $update, int $reviewId, string $fill): void
    {
        Cache::put(
            app(BotCommandService::class)->pendingInputKey($bot, $update->recipientId),
            [
                'review_id' => $reviewId,
                'fill' => $fill,
                'message_id' => $update->messageId,
            ],
            now()->addMinutes(30),
        );

        $this->botRegistry->driver($bot)->sendMessage($bot, new OutgoingBotMessage(
            recipientId: $update->recipientId,
            text: 'Пожалуйста, введите отчет в течение 30 минут:',
            replyToMessageId: $update->messageId,
        ));

        $this->botRegistry->driver($bot)->answerCallback($bot, $update, 'Ждём ваш отчет...');
    }

    public function saveReport(BotDefinition $bot, IncomingBotUpdate $update, int $reviewId, string $fill, ?string $replyToMessageId): void
    {
        $review = Review::findOrFail($reviewId);

        $updateFills = [
            $fill => $update->text,
        ];

        if ($fill === 'control_review') {
            $updateFills['end_work_on'] = new DateTime;
        }

        $review->updateOrFail($updateFills);

        $this->botRegistry->driver($bot)->sendMessage($bot, new OutgoingBotMessage(
            recipientId: $update->recipientId,
            text: 'Отчет принят',
            replyToMessageId: $replyToMessageId,
        ));

        $this->refreshReviewMessages($reviewId);

        if ($fill === 'control_review') {
            BotMessage::query()
                ->where('review_id', $reviewId)
                ->whereHas('user', fn ($query) => $query->whereIn('role', [UserRoleEnum::Founder->name, UserRoleEnum::Ssm->name]))
                ->get()
                ->each(function (BotMessage $message): void {
                    $targetBot = $this->botRegistry->find($message->bot);

                    $this->botRegistry->driver($targetBot)->sendMessage($targetBot, new OutgoingBotMessage(
                        recipientId: $message->recipient_id,
                        text: '☕ Управляющий оставил комментарий',
                        replyToMessageId: $message->message_id,
                    ));
                });
        }
    }

    public function hideButtons(BotDefinition $bot, IncomingBotUpdate $update): void
    {
        if ($update->messageId === null) {
            return;
        }

        $message = BotMessage::query()
            ->where('driver', $bot->driver)
            ->where('bot', $bot->name)
            ->where('recipient_id', $update->recipientId)
            ->where('message_id', $update->messageId)
            ->first();

        if (! $message instanceof BotMessage) {
            return;
        }

        $this->botRegistry->driver($bot)->answerCallback($bot, $update, 'Скрыто');
        $this->botNotificationService->refreshStoredMessage($message, false);
    }

    public function reviewButtons(string $role, int $reviewId, bool $afterReview = false): array
    {
        if ($role === UserRoleEnum::Control->name && ! $afterReview) {
            return [
                new BotButton('🔧', $this->payloadService->make('handle_work_start', ['review_id' => $reviewId])),
                new BotButton('👁️', $this->payloadService->make('handle_no_work_required', ['review_id' => $reviewId])),
            ];
        }

        if ($role === UserRoleEnum::Control->name && $afterReview) {
            return [
                new BotButton('✏', $this->payloadService->make('handle_report_insert', ['review_id' => $reviewId, 'fill' => 'control_review'])),
                new BotButton('❌', $this->payloadService->make('handle_hide_buttons')),
            ];
        }

        return [];
    }

    protected function refreshReviewMessages(int $reviewId): void
    {
        BotMessage::query()
            ->where('review_id', $reviewId)
            ->get()
            ->each(fn (BotMessage $message) => $this->botNotificationService->refreshStoredMessage($message));
    }

    protected function syncCallbackMessageId(BotDefinition $bot, IncomingBotUpdate $update, int $reviewId): void
    {
        if ($bot->driver !== 'vk' || $update->messageId === null) {
            return;
        }

        BotMessage::query()
            ->where('review_id', $reviewId)
            ->where('driver', $bot->driver)
            ->where('bot', $bot->name)
            ->where('recipient_id', $update->recipientId)
            ->update([
                'message_id' => $update->messageId,
            ]);
    }
}
