<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Factory\SimpleMessageDtoFactory;
use App\Dto\Telegram\Factory\SubscribeMessageDtoFactory;
use App\Enums\MessageToUser;
use App\Exeptions\Telegram\NullUsernameException;
use App\Services\TelegramService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class SubscribeUserUseCase
{
    public function __construct(
        protected TelegramService $telegram,
        protected SubscribeMessageDtoFactory $messageDtoFactory,
        protected SimpleMessageDtoFactory $answerDtoFactory,
        protected Api $telegramApi,
    ) {
    }

    public function use(Update $update): void
    {
        try {
            $messageDto = $this->messageDtoFactory->fromUpdate($update);

            $this->telegram->subscribe($messageDto);

            $this->answerDtoFactory->fromUpdateAndText($update, MessageToUser::SuccesfulSubcribe->value);

            $answerText = MessageToUser::SuccesfulSubcribe->value;

        } catch (NullUsernameException $exception) {
            Log::warning($exception);
            $answerText = MessageToUser::NoUsername->value;
        } catch (ModelNotFoundException $exception) {
            Log::warning($exception);
            $answerText = MessageToUser::UserNotRegister->value;
        } catch (\Throwable $exception) {
            Log::warning($exception);
            $answerText = MessageToUser::Error->value;
        } finally {
            $answerDto = $this->answerDtoFactory->fromUpdateAndText($update, $answerText);
            $this->telegram->sendSimpleMessage($answerDto);
        }
    }
}
