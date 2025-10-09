<?php

namespace App\Dto\Telegram\Entity;

use DateTime;

class ReviewInfoDto
{
    public function __construct(
        public string $id,
        public string $text,
        public string $rating,
        public string $sender,
        public DateTime $time,
        public string $resource,
        public string $totalsRate,
        public ?string $finalAnswer = '',
        public ?string $controlReview = '',
        public ?DateTime $answerDate = null,
        public bool $isOnCHeck = false,
        public ?string $link = null,
        public ?array $photos = null,
        public bool $isEdited = false,
        public ?BranchDto $branchDto = null,
        public ?int $dbId = null,
        public bool $updateOnlySmmMessage = false,
    ) {
    }

    public function getDateHumanFormat(): string
    {
        $ru_month = array(
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        );
        $en_month = array(
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        );

        $date = $this->time->format('d F Y, H:i');
        return str_replace($en_month, $ru_month, $date);
    }

    public function getTelegramFormat(): string
    {
        $markers = ($this->isEdited ? "Измененный " : null) . ($this->isOnCHeck ? "Непотвержденный " : null);
        $stars = str_repeat('⭐', (int)$this->rating) . " ({$this->rating} из 5)";
        $controlReview = $this->controlReview ? "☕️ Комментарий управляющего:<br/>{$this->controlReview}" : null;

        $text = $this->text ? e(<<<EOF
📝 {$markers}Отзыв:
{$this->text}
EOF): "";


        return <<<EOF
☕ Кофейня: #{$this->branchDto?->name}
👤 Управляющий: {$this->branchDto?->upr}
📣 Платформа: <a href="{$this->link}">{$this->resource}</a>
📆 Дата: {$this->getDateHumanFormat()}
✏ Оценка: {$this->totalsRate} {$stars}

{$text}

{$controlReview}
EOF;
//        return "☕ Кофейня: #{$this->branchDto?->name}"
//            . "\n👤 Управляющий: {$this->branchDto?->upr}"
//            . "\n📣 Платформа: <a href=\"{$this->link}\">{$this->resource}</a>"
//            . "\n📆 Дата: {$this->getDateHumanFormat()}"
//            . "\n✏ Оценка:" . " ({$this->totalsRate}) " . str_repeat('⭐', (int)$this->rating) . "({$this->rating} из 5)\n\n"
//            . ($this->isEdited ? "Измененный " : null) . ($this->isOnCHeck ? "Непотвержденный " : null)
//            . ($this->text ? " 📝 Отзыв:\n {$this->text}" : "");
    }
}
