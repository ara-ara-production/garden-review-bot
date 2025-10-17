<?php

namespace App\UseCases\Telegram;

use App\Dto\Telegram\Factory\SimpleMessageDtoFactory;
use App\Dto\Telegram\Factory\SubscribeMessageDtoFactory;
use App\Enums\MessageToUser;
use App\Enums\UserRoleEnum;
use App\Exeptions\Telegram\NullUsernameException;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class SayAllaudUseCase
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

            $user = $this->telegram->getUser($messageDto);

            if (!collect([UserRoleEnum::Founder->name, UserRoleEnum::Ssm->name])->has($user->role)
                || is_null(!$user->email)
            ) {
                throw new ModelNotFoundException('Нет разрешения на данное действие!');
            }

            $text = $update->getMessage()->getText();

            // убираем '/feedback ' из начала, чтобы получить только текст
            $text = trim(str_replace('/say_allaud', '', $text));

            if (empty($feedback)) {
                $answerText = "Отсутсвует текст сообщения!";
                return;
            }

            $toSendUser = User::query()
                ->select('telegram_chat')
                ->whereNot('id', $user->id)
                ->whereNotNull('telegram_chat')
                ->where('role', UserRoleEnum::Control->name)
                ->get();

            $toSendUser->each(function ($user) use ($text) {
                $this->telegram->sendSimpleMessage(
                    $this->answerDtoFactory->fromRawParameters(
                        $user->telegram_chat,
                        $text
                    )
                );
            });

            $answerText = "Сообщение отправлено";
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
