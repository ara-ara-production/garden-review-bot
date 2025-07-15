<?php

namespace App\Services;

use App\Dto\Telegram\Entity\ReviewInfoDto;
use App\Dto\Telegram\Entity\SubscribeMessageDto;
use App\Dto\Telegram\Entity\TelegramMessage;
use App\Dto\Telegram\Entity\TelegramMessageReviewDto;
use App\Dto\Telegram\Factory\NotifyMediaGroupDtoFactory;
use App\Dto\Telegram\Factory\NotifyMessageDtoFactory;
use App\Dto\Telegram\Factory\TelegramMessageReviewDtoFactory;
use App\Dto\User\Entity\ForNotifyDto;
use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class TelegramService
{
    public function __construct(
        protected Api $telegram,
        protected NotifyMediaGroupDtoFactory $mediaGroupDtoFactory,
        protected NotifyMessageDtoFactory $messageDtoFactory,
        protected TelegramMessageReviewDtoFactory $telegramMessageReviewDtoFactory
    ) {
    }

    public function sendSimpleMessage(TelegramMessage $dto): Message
    {
        return $this->telegram->sendMessage((array)$dto);
    }

    public function sendMediaMessage(TelegramMessage $dto): Message
    {
        return $this->telegram->sendMediaGroup((array)$dto);
    }


    /**
     * @throws \Throwable если не удалось созранить
     * @throws ModelNotFoundException если не удалось найти пользователя
     */
    public function getUser(SubscribeMessageDto $dto)
    {
        return User::byTgUsername($dto->username)->firstOrFail();
    }

    /**
     * @throws \Throwable если не удалось созранить
     * @throws ModelNotFoundException если не удалось найти пользователя
     */
    public function subscribe(SubscribeMessageDto $dto): void
    {
        User::byTgUsername($dto->username)
            ->firstOrFail()
            ->updateOrFail(['telegram_chat' => $dto->chatId]
            );
    }

    public function firstNotify(Collection $dtos): Collection
    {
        $users = User::toNotify();

        $telegramMessagesToStore = collect();

        $dtos->each(
            fn(ReviewInfoDto $dto) => $users->each(
                fn(ForNotifyDto $user) => $telegramMessagesToStore
                    ->push($this->sendReviewNotify($dto, $user))
            )
        );

        return $telegramMessagesToStore;
    }

    protected function sendReviewNotify(ReviewInfoDto $dto, ForNotifyDto $user): ?TelegramMessageReviewDto
    {

        if (!((
            $user->role === UserRoleEnum::Control->name
            && User::findOrFail($user->id)->brunches()->where('id', $dto->branchDto->id)->exists()
            )
            || $user->role !== UserRoleEnum::Control->name)) {
            return null;
        }

        $message = null;
        if (!empty($dto->photos)) {
            $message = $this->sendMediaMessage($this->mediaGroupDtoFactory->fromDtos($dto, $user));
        }

        if (mb_strlen($dto->getTelegramFormat()) >= 1024 || empty($dto->photos)) {
            $message = $this->sendSimpleMessage($this->messageDtoFactory->fromDtos($dto, $user));
        }
        return $message->get('message_id') ? $this->telegramMessageReviewDtoFactory->fromData($dto, $message, $user) : null;
    }

    /*public function changeButtonsOnMessages(Review $review, string $action, int $callbackId)
    {
        $messages = collect($review->message_id);

        $chatIds = $messages->pluck('chat_id');

        $roles = User::select('role', 'telegram_chat')->whereIn('telegram_chat', $chatIds)->get()->toArray();

        $roles = array_column($roles, 'role', 'telegram_chat');

        $messages = $messages->filter(function ($message) use ($roles) {
            return $roles[$message['chat_id']] === UserRoleEnum::Control;
        });

        $newKeyboard = $action === 'startWorkOn'
            ? Keyboard::make()->inline()
                ->row([
                    Keyboard::inlineButton(
                        [
                            'text' => 'Что было сделано?',
                            'callback_data' => json_encode(
                                ['action' => 'inputControlReview', 'review_id' => $review->id]
                            ),
                        ]
                    ),
                ])
            : null;

        $messages->each(function ($message) use ($newKeyboard, $callbackId) {
            $this->telegram->editMessageReplyMarkup([
                'chat_id' => $message['chat_id'],
                'message_id' => $message['message_id'],
                'reply_markup' => $newKeyboard,
            ]);

            $this->telegram->answerCallbackQuery([
                'callback_query_id' => $callbackId,
                'text' => 'Сохранено!',
            ]);
        });
    }*/
}
