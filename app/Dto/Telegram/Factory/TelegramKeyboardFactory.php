<?php

namespace App\Dto\Telegram\Factory;

use Telegram\Bot\Keyboard\Keyboard;

class TelegramKeyboardFactory
{
    public function forControlFirstNotify(int $reviewId)
    {
        return Keyboard::make()->inline()
            ->row([
                Keyboard::inlineButton(
                    [
                        'text' => '🔧',
                        'callback_data' => "action:handle_work_start|review_id:{$reviewId}"
                    ]
                ),
                Keyboard::inlineButton([
                    'text' => '👁️',
                    'callback_data' => "action:handle_no_work_required|review_id:{$reviewId}"
                ])
            ]);
    }

    public function forControlSetReview(int $reviewId)
    {
        return Keyboard::make()->inline()
            ->row([
                Keyboard::inlineButton(
                    [
                        'text' => '✏',
                        'callback_data' => "action:handle_report_insert|review_id:{$reviewId}|fill:control_review"
                    ]
                ),
            ]);
    }

    public function forControlAfterReview(int $reviewId)
    {
        return Keyboard::make()->inline()
            ->row([
                Keyboard::inlineButton(
                    [
                        'text' => '✏',
                        'callback_data' => "action:handle_report_insert|review_id:{$reviewId}|fill:control_review"
                    ]
                ),
                Keyboard::inlineButton(
                    [
                        'text' => '❌',
                        'callback_data' => "action:handle_hide_buttons"
                    ]
                ),
            ]);
    }
}
