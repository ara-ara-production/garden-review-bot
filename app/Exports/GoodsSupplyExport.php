<?php

namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class GoodsSupplyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithColumnFormatting, WithEvents
{
    public function __construct(
        protected Collection $collection
    ) {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            '#',
            'Дата публикации отзыва',
            'Дата начала проверки',
            'Дата завершения проверки',
            'Платформа',
            'Филиал',
            'Текущий рейтинг',
            'Оценка',
            'Отзыв',
            'Комментарий управляющего',
            'Ответ SMM на платформе'
        ];
    }

    public function map($row): array
    {
        return [
            $row->review_id,
            $row->posted_at ? Date::dateTimeToExcel($row->posted_at) : null,
            $row->start_work_on ? Date::dateTimeToExcel($row->start_work_on) : null,
            $row->end_work_on ? Date::dateTimeToExcel($row->end_work_on) : null,
            $row->resource,
            $row->brunch_name,
            $row->total_brunch_rate,
            $row->score,
            $row->comment,
            $row->control_review,
            $row->final_answer,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'dd.mm.yyyy hh:mm',
            'C' => 'dd.mm.yyyy hh:mm',
            'D' => 'dd.mm.yyyy hh:mm',                          // 25.09.2025 14:35
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 📌 Заморозить первую строку (чтобы заголовки не прокручивались)
                $sheet->freezePane('A2');

                // 📌 Добавить автофильтр ко всем колонкам заголовков
                $highestColumn = $sheet->getHighestColumn(); // например "D"
                $highestRow = $sheet->getHighestRow();       // число строк
                $sheet->setAutoFilter("A1:{$highestColumn}1");
            },
        ];
    }
}
