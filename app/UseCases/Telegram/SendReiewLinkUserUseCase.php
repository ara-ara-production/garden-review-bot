<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Factory\SimpleMessageDtoFactory;
use App\Dto\Telegram\Factory\SubscribeMessageDtoFactory;
use App\Enums\MessageToUser;
use App\Exeptions\Telegram\NullUsernameException;
use App\Services\ReviewService;
use App\Services\TelegramService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class SendReiewLinkUserUseCase
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

            $this->telegram->getUser($messageDto);

//            $this->answerDtoFactory->fromUpdateAndText($update, );

            $token = app(ReviewService::class)->getUrlToken();
            $answerText = "Ссылка:\nhttps://bot-reviewer.ru/{$token}/reviews";

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
