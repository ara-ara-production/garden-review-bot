<?php

namespace App\Dto\Telegram\Factory;

use App\Dto\Telegram\Entity\CallbackQueryDto;
use App\Dto\Telegram\Entity\FillReportPayloadDto;
use App\Jobs\HandleNoWorkRequired;
use App\Jobs\HandleReportInsert;
use App\Jobs\HandleWorkStart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CallbackQueryDtoFactory
{
    public function fromData(Collection $callback): CallbackQueryDto
    {
        $arrayData = $this->parseCallbackData($callback->get('data'));

        $action = $arrayData['action'];

        Log::debug($action);

        $payloadDtoFactory = match ($action) {
            'handle_no_work_required', 'handle_work_start' => app(ReviewIdPayloadFactory::class),
            'handle_report_insert' => app(FillReportPayloadDtoFactory::class),
        };

        return new CallbackQueryDto(
            $callback->get('id'),
            $this->matchKeyToClass($arrayData['action']),
            $payloadDtoFactory->make($arrayData)
        );
    }

    public function matchKeyToClass(string $key): string
    {
        Log::debug($key);
        return match ($key) {
            'handle_no_work_required' => HandleNoWorkRequired::class,
            'handle_work_start' => HandleWorkStart::class,
            'handle_report_insert' => HandleReportInsert::class,
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
