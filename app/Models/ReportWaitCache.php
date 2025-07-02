<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportWaitCache extends Model
{
    protected $fillable = [
        'user_id',
        'review_id'
    ];
}
