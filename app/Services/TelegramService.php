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
        $mediaContent = (array)$dto;
        unset($mediaContent['text']);
        unset($mediaContent['parse_mode']);

        $textContent = (array)$dto;
        unset($textContent['media']);

        $message = $this->telegram->sendMediaGroup($mediaContent);

        $textContent['reply_to_message_id'] = $message[0]['message_id'];

        return $this->telegram->sendMessage($textContent);
    }


    /**
     * @throws \Throwable если не удалось созранить
     * @throws ModelNotFoundException если не удалось найти пользователя
     */
    public function getUser(
        SubscribeMessageDto $dto
    ) {
        return User::where('telegram_chat', $dto->chatId)->firstOrFail();
    }

    /**
     * @throws \Throwable если не удалось созранить
     * @throws ModelNotFoundException если не удалось найти пользователя
     */
    public function subscribe(
        SubscribeMessageDto $dto
    ): void {
        User::byTgUsername($dto->username)
            ->firstOrFail()
            ->updateOrFail(['telegram_chat' => $dto->chatId]
            );
    }

    public function firstNotify(
        Collection $dtos
    ): Collection {
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

    protected function sendReviewNotify(
        ReviewInfoDto $dto,
        ForNotifyDto $user
    ): ?TelegramMessageReviewDto {
        if (!(($user->role === UserRoleEnum::Control->name && (User::findOrFail($user->id)->brunches()->where(
                        'id',
                        $dto->branchDto->id
                    )->exists() || User::findOrFail($user->id)->brunchesPupr()->where(
                        'id',
                        $dto->branchDto->id
                    )->exists())) || $user->role !== UserRoleEnum::Control->name)) {
            return null;
        }

        $message = !empty($dto->photos)
            ? $this->sendMediaMessage($this->mediaGroupDtoFactory->fromDtos($dto, $user))
            : $this->sendSimpleMessage($this->messageDtoFactory->fromDtos($dto, $user));

        return $message->get('message_id') ? $this->telegramMessageReviewDtoFactory->fromData(
            $dto,
            $message,
            $user
        ) : null;
    }
}
