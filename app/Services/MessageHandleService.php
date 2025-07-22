<?php

namespace App\Services;

use App\Dto\Telegram\Entity\CallbackQueryDto;
use App\Dto\Telegram\Entity\CallbackQueryPayloadDto;
use App\Dto\Telegram\Entity\FillReportPayloadDto;
use App\Dto\Telegram\Entity\ReviewIdPayloadDto;
use App\Dto\Telegram\Entity\UpdateDto;
use App\Dto\Telegram\Factory\TelegramKeyboardFactory;
use App\Enums\MessageToUser;
use App\Enums\UserRoleEnum;
use App\Exeptions\Telegram\NullPayloadException;
use App\Exeptions\Telegram\WrongPayload;
use App\Models\Review;
use App\Models\TelegramMessage;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Throwable;

class MessageHandleService
{
    public function __construct(
        protected Api $telegram,
        protected TelegramKeyboardFactory $telegramKeyboardFactory,
    )
    {
    }

    /**
     * @throws WrongPayload
     * @throws Throwable
     */
    public function markReviewToNoNeedWork(CallbackQueryPayloadDto $dto): void
    {
        if (!($dto instanceof ReviewIdPayloadDto)) {
            throw new WrongPayload(ReviewIdPayloadDto::class, $dto::class);
        }

        /** @var Review $review */
        $review = Review::findOrFail($dto->reviewId);

        $dateTime = new DateTime();
        $review->updateOrFail([
            'start_work_on' => $dateTime,
            'end_work_on' => $dateTime,
            'control_review' => MessageToUser::NoWorkNeeded->value
        ]);
    }

    /**
     * @throws WrongPayload
     * @throws Throwable
     */
    public function markReviewNeedWork(CallbackQueryPayloadDto $dto): void
    {
        if (!($dto instanceof ReviewIdPayloadDto)) {
            throw new WrongPayload(ReviewIdPayloadDto::class, $dto::class);
        }

        /** @var Review $review */
        $review = Review::findOrFail($dto->reviewId);

        $dateTime = new DateTime();
        $review->updateOrFail([
            'start_work_on' => $dateTime,
        ]);
    }

    /**
     * @throws TelegramSDKException
     */
    public function replyToCallback(CallbackQueryDto $dto, $text): void
    {
        $this->telegram->answerCallbackQuery([
            'callback_query_id' => $dto->id,
            'text' => $text
        ]);
    }

    /**
     * @throws WrongPayload|TelegramSDKException
     */
    public function clearInlineButtons(CallbackQueryPayloadDto $dto): void
    {
        if (!($dto instanceof ReviewIdPayloadDto)) {
            throw new WrongPayload(ReviewIdPayloadDto::class, $dto::class);
        }

        TelegramMessage::where('review_id', $dto->reviewId)
            ->where('role', UserRoleEnum::Control->name)
            ->leftJoin('users', 'users.id', '=', 'telegram_messages.user_id')
            ->get()
            ->each(fn(TelegramMessage $message) => $this->telegram->editMessageReplyMarkup([
                'chat_id' => $message->user->telegram_chat,
                'message_id' => $message->message_id,
                'reply_markup' => null,
            ]));
    }

    public function setInlineButtonForControlReview(CallbackQueryPayloadDto $dto): void
    {
        if (!($dto instanceof ReviewIdPayloadDto)) {
            throw new WrongPayload(ReviewIdPayloadDto::class, $dto::class);
        }

        TelegramMessage::where('review_id', $dto->reviewId)
            ->where('role', UserRoleEnum::Control->name)
            ->leftJoin('users', 'users.id', '=', 'telegram_messages.user_id')
            ->get()
            ->each(fn(TelegramMessage $message) => $this->telegram->editMessageReplyMarkup([
                'chat_id' => $message->user->telegram_chat,
                'message_id' => $message->message_id,
                'reply_markup' => $this->telegramKeyboardFactory->forControlSetReview($dto->reviewId),
            ]));
    }

    public function makeInputDialog(UpdateDto $dto): void
    {
        $payload = $dto->callback_query->data;
        if (!($payload instanceof FillReportPayloadDto)) {
            throw new WrongPayload(FillReportPayloadDto::class, $payload::class);
        }

        $cacheKey = "chatId-{$dto->chat_id}";
        Cache::put($cacheKey, $dto, now()->addMinutes(30));

        $this->telegram->sendMessage([
            'chat_id' => $dto->chat_id,
            'text' => 'Пожалуйста, введите отчет в течении 30 минут:',
            'resize_keyboard' => true,
        ]);

        $this->telegram->answerCallbackQuery([
            'callback_query_id' => $dto->callback_query->id,
            'text' => 'Ждём ваш отчет...',
        ]);
    }

    /**
     * @throws NullPayloadException
     * @throws Throwable
     * TODO: разбить на этапы все же
     */
    public function getReport(UpdateDto $dto): void
    {
        if (!$dto->message) {
            return;
        }

        $cacheKey = "chatId-{$dto->chat_id}";

        /** @var UpdateDto $payload */
        $payload = Cache::get($cacheKey);

        /** @var FillReportPayloadDto  $callbackPayLoad */
        $callbackPayLoad = $payload->callback_query->data;

        if (!$payload) {
            throw new NullPayloadException();
        }

        Cache::forget($cacheKey);

        /** @var Review $review */
        $review = Review::findOrFail($callbackPayLoad->reviewId);


        $updateFills = [
            $callbackPayLoad->fill => $dto->message,
        ];

        $text = "Ответ SMM:\n";
        $roles = [UserRoleEnum::Founder->name];
        if ($callbackPayLoad->fill === 'control_review') {
            $updateFills['end_work_on'] = new DateTime();

            $text = "☕ Ревью управляющего:\n";
            $roles[] = UserRoleEnum::Ssm->name;
        }

        $review->updateOrFail($updateFills);

        $this->telegram->sendMessage([
            'chat_id' => $dto->chat_id,
            'text' => 'Отчет принят',
            'reply_to_message_id' => $payload->message_id,
        ]);

        if (!$payload->message) {
            throw new NullPayloadException();
        }

        $message = $payload->message;

        $message = substr($message, 0, (strpos($message, "\n\n☕ Ревью управляющего:\n") ?: strlen($message)));
        $message .= "\n\n☕ Ревью управляющего:\n{$dto->message}";

        $this->telegram->editMessageText([
            'chat_id' => $dto->chat_id,
            'message_id' => $payload->message_id,
            'text' => $message,
            'reply_markup' => $this->telegramKeyboardFactory->forControlAfterReview($review->id),
        ]);

        /** @var Collection<TelegramMessage> $replyMessages */
        $replyMessages = TelegramMessage::where('review_id', $callbackPayLoad->reviewId)
            ->whereIn('users.role', $roles)
            ->leftJoin('users', 'users.id', '=', 'telegram_messages.user_id')
            ->get();

        Log::debug(var_export($replyMessages, true));

        if (!$replyMessages) {
            throw new ModelNotFoundException('cant get founder messages');
        }

        $replyMessages->each(fn(TelegramMessage $message)
        => $this->telegram->sendMessage([
            'chat_id' => $message->user->telegram_chat,
            'text' => $text . $dto->message,
            'reply_to_message_id' => $message->message_id
        ]));
    }

    public function hideButtons(UpdateDto $dto): void
    {
        $this->telegram->editMessageReplyMarkup([
            'chat_id' => $dto->chat_id,
            'message_id' => $dto->message_id,
            'reply_markup' => null,
        ]);
    }
}
