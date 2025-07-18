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
        $message = $this->telegram->sendMediaGroup((array)$dto);
        if ($dto->reply_markup !== null) {
            $this->telegram->sendMessage([
                'chat_id' => $dto->chat_id,
                'text' => '^',
                'reply_markup' => $dto->reply_markup,
            ]);
        }
        return $message;
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
        $message = null;
        if (!empty($dto->photos)) {
            $message = $this->sendMediaMessage($this->mediaGroupDtoFactory->fromDtos($dto, $user));
        }

        if (mb_strlen($dto->getTelegramFormat()) >= 1024 || empty($dto->photos)) {
            $message = $this->sendSimpleMessage($this->messageDtoFactory->fromDtos($dto, $user));
        }
        return $message->get('message_id') ? $this->telegramMessageReviewDtoFactory->fromData(
            $dto,
            $message,
            $user
        ) : null;
    }
}
