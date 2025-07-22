<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder getDataForIndex()
 */
class Review extends Model
{
    protected $fillable = [
        'key',
        'resource',
        'posted_at',
        'brunch_id',
        'score',
        'comment',
        'sender',
        'link',
        'start_work_on',
        'end_work_on',
        'control_review',
        'final_answer',
        'message_id',
        'final_answer',
        'is_edited',
        'is_on_check',
        'total_brunch_rate',
    ];

    public function casts()
    {
        return [
            'posted_at' => 'datetime',
            'start_work_on' => 'datetime',
            'end_work_on' => 'datetime',
            'message_id' => 'array'
        ];
    }

    public function scopeGetDataForIndex(Builder $query)
    {
        $query
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->orderByDesc('posted_at')
            ->select('posted_at',
                'start_work_on',
                'end_work_on',
                'control_review',
                'score',
                'resource',
                'comment',
                'brunches.name AS brunch_name',
                'reviews.id AS review_id',
                'final_answer',
                'total_brunch_rate'
            )
        ;
    }
}
