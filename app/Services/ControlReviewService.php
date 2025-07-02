<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Models\ReportWaitCache;
use App\Models\Review;
use App\Models\User;
use DateTime;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class ControlReviewService
{
    public function __construct(
        protected Api $telegram
    ) {
    }

    public function     markWork(array $data): Review
    {
        /** @var Review $review */
        $review = Review::find($data['review_id']);

        if (!$review) {
            throw new \Exception('Отзыв не найден');
        }

        $review->update([
            'start_work_on' => new \DateTime(),
            'control_review' => ($data['action'] === 'noWorkOn' ? 'Нет необходимости работе' : null)
        ]);

        return $review;
    }

    public function getInputDialog($callback): void
    {
        $chatId = $callback['message']['chat']['id'];
        $userId = $callback['from']['id'];
        $data = json_decode($callback['data'], true);

        ReportWaitCache::create([
            'user_id' => $userId,
            'review_id' => $data['review_id'],
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Пожалуйста, введите отчет:',
            'resize_keyboard' => true,
        ]);

        $this->telegram->answerCallbackQuery([
            'callback_query_id' => $callback['id'],
            'text' => 'Ждём ваш отчет...',
        ]);
    }

    public function getReportOnReview($update): void
    {
        $message = $update->getMessage();

        if (!$message) {
            return;
        }

        $userId = $message->from->id;
        $text = $message->text;

        $cacheItem = ReportWaitCache::where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$cacheItem) {
            return;
        }

        $reviewId = $cacheItem->review_id;

        $review = Review::find($reviewId);

        if (!$review) {
            return;
        }

        $review->control_review = $text;
        $review->end_work_on = new DateTime();
        $status = $review->save();

        if ($status) {
            $this->telegram->sendMessage([
                'chat_id' => $message['chat']['id'],
                'text' => 'Отчет принят',
            ]);

            $cacheItem->delete();
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $message['chat']['id'],
                'text' => 'Возникла ошибка, повторите позже',
            ]);
        }

        $messages = $review->message_id;
        $founderChatIds = User::chatIdByRole([UserRoleEnum::Founder->name])->toArray();
        $founderMessages = collect($messages)->filter(fn($message) => in_array($message['chat_id'], $founderChatIds));

        $smmChatIds = User::chatIdByRole([UserRoleEnum::Ssm->name])->toArray();
        $smmMessages = collect($messages)->filter(fn($message) => in_array($message['chat_id'], $smmChatIds));

        $founderMessages->each(fn($message) => $this->telegram->sendMessage([
            'chat_id' => $message['chat_id'],
            'text' => "☕ Ревью управляющего:\n" . $text,
            'reply_to_message_id' => $message['message_id'],
        ]));

        $keyboard = Keyboard::make()->inline()
            ->row([
                Keyboard::inlineButton(
                    [
                        'text' => 'Финальный ответ',
                        'callback_data' => json_encode(['action' => 'setFinalAnswer', 'review_id' => $review->dbId]),
                    ]
                ),
            ]);

        $smmMessages->each(fn($message) => $this->telegram->sendMessage([
            'chat_id' => $message['chat_id'],
            'text' => "☕ Ревью управляющего:\n" . $text,
            'reply_to_message_id' => $message['message_id'],
            ''
        ]));
    }

    public function getFinalAnswerOnReview($update): void
    {
        $message = $update->getMessage();

        if (!$message) {
            return;
        }

        $userId = $message->from->id;
        $text = $message->text;

        $cacheItem = ReportWaitCache::where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$cacheItem) {
            return;
        }

        $reviewId = $cacheItem->review_id;

        $review = Review::find($reviewId);

        if (!$review) {
            return;
        }

        $review->final_answer = $text;
        $status = $review->save();

        if ($status) {
            $this->telegram->sendMessage([
                'chat_id' => $message['chat']['id'],
                'text' => 'Отчет принят',
            ]);

            $cacheItem->delete();
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $message['chat']['id'],
                'text' => 'Возникла ошибка, повторите позже',
            ]);
        }

        $messages = $review->message_id;
        $founderChatIds = User::chatIdByRole([UserRoleEnum::Founder->name])->toArray();
        $founderMessages = collect($messages)->filter(fn($message) => in_array($message->chat_id, $founderChatIds));

        $founderMessages->each(fn($message) => $this->telegram->sendMessage([
            'chat_id' => $message->chat_id,
            'text' => "🕊️ Ответ гостью:\n" . $text,
            'reply_to_message_id' => $message->message_id,
        ]));
    }
}
