<?php

namespace App\Exports;

use App\Models\Review;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReviewExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Review::all();
    }
}
