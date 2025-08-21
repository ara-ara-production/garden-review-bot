<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\CallbackQueryDto;
use App\Dto\Telegram\Entity\FillReportPayloadDto;
use App\Jobs\HandleNoWorkRequired;
use App\Jobs\HandleReportInsert;
use App\Jobs\HandleWorkStart;
use App\UseCases\Telegram\HideKeyboardUseCase;
use App\UseCases\Telegram\InsertReportUseCase;
use App\UseCases\Telegram\NotifyAboutNoWorkRequiredUseCase;
use App\UseCases\Telegram\NotifyAboutWorkStart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CallbackQueryDtoFactory
{
    public function fromData(Collection $callback): CallbackQueryDto
    {
        $arrayData = $this->parseCallbackData($callback->get('data'));

        $action = $arrayData['action'];

        $payloadDtoFactory = match ($action) {
            'handle_no_work_required', 'handle_work_start' => app(ReviewIdPayloadFactory::class),
            'handle_report_insert' => app(FillReportPayloadDtoFactory::class),
            'handle_hide_buttons' => app(NullPayloadDtoFactory::class),
            default => null,
        };

        return new CallbackQueryDto(
            $callback->get('id'),
            $this->matchKeyToClass($arrayData['action']),
            $payloadDtoFactory->make($arrayData)
        );
    }

    public function matchKeyToClass(string $key): string
    {
//        return match ($key) {
//            'handle_no_work_required' => HandleNoWorkRequired::class,
//            'handle_work_start' => HandleWorkStart::class,
//            'handle_report_insert' => HandleReportInsert::class,
//            default => null,
//        };

        return match ($key) {
            'handle_no_work_required' => NotifyAboutNoWorkRequiredUseCase::class,
            'handle_work_start' => NotifyAboutWorkStart::class,
            'handle_report_insert' => InsertReportUseCase::class,
            'handle_hide_buttons' => HideKeyboardUseCase::class,
            default => null,
        };
    }

    public function parseCallbackData(string $data): array
    {
        $result = [];

        // Разделяем строку по "|"
        $parts = explode('|', $data);

        foreach ($parts as $part) {
            // Разделяем каждую часть по ":"
            [$key, $value] = explode(':', $part, 2);
            $result[$key] = $value;
        }

        return $result;
    }
}
