<?php

namespace App\Bot\Services;

use App\Dto\Telegram\Entity\ReviewInfoDto;

class BotReviewFormatter
{
    public function format(ReviewInfoDto $reviewInfoDto, string $driver): string
    {
        if ($driver === 'telegram') {
            return $reviewInfoDto->getTelegramFormat();
        }

        $markers = trim(($reviewInfoDto->isEdited ? 'Измененный ' : '').($reviewInfoDto->isOnCHeck ? 'Неподтвержденный' : ''));
        $stars = str_repeat('⭐', (int) $reviewInfoDto->rating)." ({$reviewInfoDto->rating} из 5)";
        $resource = $reviewInfoDto->resource === '2Гис' ? '2ГИС' : $reviewInfoDto->resource;
        $extraInfo = $reviewInfoDto->resource === 'Яндекс.Еда' && $reviewInfoDto->extraData
            ? "\nЗаказ {$reviewInfoDto->extraData}"
            : '';
        $text = $reviewInfoDto->text ? "\n\n📝 Отзыв:\n{$reviewInfoDto->text}" : '';
        $controlReview = $reviewInfoDto->controlReview ? "\n\n☕ Комментарий управляющего:\n{$reviewInfoDto->controlReview}" : '';

        return trim(implode("\n", array_filter([
            "☕ {$reviewInfoDto->branchDto?->name}",
            "🤵 {$reviewInfoDto->branchDto?->upr}",
            "📣 {$resource}",
            $reviewInfoDto->link,
            $extraInfo !== '' ? trim($extraInfo) : null,
            "📆 {$reviewInfoDto->getDateHumanFormat()}",
            "👤 {$reviewInfoDto->sender}",
            "✏ {$reviewInfoDto->totalsRate} {$stars}",
            $markers !== '' ? $markers : null,
        ])).$text.$controlReview);
    }
}
