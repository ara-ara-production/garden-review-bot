<?php

namespace App\Bot\Services;

use App\Bot\Data\OutgoingBotMessage;
use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\Telegram\Factory\ReviewInfoDtoFactory;
use App\Enums\UserRoleEnum;
use App\Models\BotMessage;
use App\Models\BotSubscription;
use App\Models\Review;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Log;

class BotNotificationService
{
    public function __construct(
        protected BotRegistry $botRegistry,
        protected BotReviewFormatter $botReviewFormatter,
        protected ReviewInfoDtoFactory $reviewInfoDtoFactory,
    ) {}

    public function notifyAboutReview(ReviewInfoDto $reviewInfoDto): void
    {
        foreach ($this->botRegistry->allEnabled() as $bot) {
            $subscriptions = BotSubscription::query()
                ->forBot($bot->driver, $bot->name)
                ->with('user')
                ->get();

            Log::info('Bot review notification dispatch started', [
                'bot' => $bot->name,
                'driver' => $bot->driver,
                'review_id' => $reviewInfoDto->dbId,
                'subscriptions_count' => $subscriptions->count(),
            ]);

            $subscriptions->each(function (BotSubscription $subscription) use ($bot, $reviewInfoDto): void {
                $user = $subscription->user;

                if (! $user instanceof User || ! $this->canReceiveReview($user, $reviewInfoDto)) {
                    Log::info('Bot review notification skipped', [
                        'bot' => $bot->name,
                        'driver' => $bot->driver,
                        'review_id' => $reviewInfoDto->dbId,
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                    ]);

                    return;
                }

                try {
                    $sentMessage = $this->botRegistry->driver($bot)->sendMessage($bot, new OutgoingBotMessage(
                        recipientId: $subscription->recipient_id,
                        text: $this->botReviewFormatter->format($reviewInfoDto, $bot->driver),
                        buttons: $this->buttonsForUser($user, $reviewInfoDto->dbId),
                    ));
                } catch (\Throwable $exception) {
                    Log::error('Bot review notification failed', [
                        'bot' => $bot->name,
                        'driver' => $bot->driver,
                        'review_id' => $reviewInfoDto->dbId,
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'recipient_id' => $subscription->recipient_id,
                        'message' => $exception->getMessage(),
                    ]);

                    return;
                }

                if ($sentMessage === null) {
                    Log::warning('Bot review notification returned empty message', [
                        'bot' => $bot->name,
                        'driver' => $bot->driver,
                        'review_id' => $reviewInfoDto->dbId,
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'recipient_id' => $subscription->recipient_id,
                    ]);

                    return;
                }

                BotMessage::create([
                    'review_id' => $reviewInfoDto->dbId,
                    'user_id' => $user->id,
                    'driver' => $bot->driver,
                    'bot' => $bot->name,
                    'recipient_id' => $sentMessage->recipientId,
                    'message_id' => $sentMessage->messageId,
                ]);
            });
        }
    }

    public function deleteReviewMessages(Review $review): void
    {
        BotMessage::query()
            ->where('review_id', $review->id)
            ->get()
            ->each(function (BotMessage $message): void {
                if (date_diff(new DateTime, $message->created_at)->days < 2) {
                    $bot = $this->botRegistry->find($message->bot);
                    $this->botRegistry->driver($bot)->deleteMessage($bot, $message->recipient_id, $message->message_id);
                }
            });

        BotMessage::query()->where('review_id', $review->id)->delete();
    }

    public function refreshStoredMessage(BotMessage $message, bool $withButtons = true): void
    {
        $review = $message->review;
        $user = $message->user;

        if (! $review instanceof Review || ! $user instanceof User) {
            return;
        }

        $reviewInfoDto = $this->reviewInfoDtoFactory->fromEntity($review);
        $bot = $this->botRegistry->find($message->bot);

        $this->botRegistry->driver($bot)->editMessage($bot, new OutgoingBotMessage(
            recipientId: $message->recipient_id,
            messageId: $message->message_id,
            text: $this->botReviewFormatter->format($reviewInfoDto, $bot->driver),
            buttons: $withButtons ? $this->buttonsForUser($user, $review->id) : [],
        ));
    }

    protected function canReceiveReview(User $user, ReviewInfoDto $reviewInfoDto): bool
    {
        if ($user->role->name !== UserRoleEnum::Control->name) {
            return true;
        }

        return $user->brunches()->where('id', $reviewInfoDto->branchDto->id)->exists()
            || $user->brunchesPupr()->where('id', $reviewInfoDto->branchDto->id)->exists();
    }

    protected function buttonsForUser(User $user, int $reviewId): array
    {
        if ($user->role->name !== UserRoleEnum::Control->name) {
            return [];
        }

        $review = Review::find($reviewId);

        if (! $review instanceof Review) {
            return [];
        }

        return app(BotInteractionService::class)->reviewButtons(
            $user->role->name,
            $reviewId,
            $review->start_work_on !== null
        );
    }
}
