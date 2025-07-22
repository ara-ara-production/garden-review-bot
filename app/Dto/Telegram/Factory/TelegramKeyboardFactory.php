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
                        'text' => 'Принял в работу',
                        'callback_data' => "action:handle_work_start|review_id:{$reviewId}"
//                        'callback_data' => json_encode([
//                            'action' => 'handle_work_start',
//                            'payload' => [
//                                'review_id' => $reviewId
//                            ],
//                        ]),
                    ]
                ),
                Keyboard::inlineButton([
                    'text' => 'Ознакомился, меры не требуется',
                    'callback_data' => "action:handle_no_work_required|review_id:{$reviewId}"
//                    'callback_data' => json_encode([
//                        'action' => 'handle_no_work_required',
//                        'payload' => [
//                            'review_id' => $reviewId
//                        ],
//                    ]),
                ])
            ]);
    }

    public function forSMM(int $reviewId)
    {
        return null;
//        return Keyboard::make()->inline()
//            ->row([
//                Keyboard::inlineButton(
//                    [
//                        'text' => 'Ответ гостю',
//                        'callback_data' => "action:handle_report_insert|review_id:{$reviewId}|fill:final_answer"
////                        'callback_data' => json_encode([
////                            'action' => 'handle_report_insert',
////                            'payload' => [
////                                'review_id' => $reviewId,
////                                'fill' => 'final_answer'
////                            ],
////                        ]),
//                    ]
//                ),
//            ]);
    }

    public function forControlSetReview(int $reviewId)
    {
        return Keyboard::make()->inline()
            ->row([
                Keyboard::inlineButton(
                    [
                        'text' => 'Что было сделано?',
                        'callback_data' => "action:handle_report_insert|review_id:{$reviewId}|fill:control_review"
//                        'callback_data' => json_encode([
//                            'action' => 'handle_report_insert',
//                            'payload' => [
//                                'review_id' => $reviewId,
//                                'fill' => 'control_review'
//                            ],
//                        ]),
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
