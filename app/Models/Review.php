<?php

namespace App\Models;

use App\Observers\ReviewObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Builder getDataForIndex()
 */

#[ObservedBy([ReviewObserver::class])]
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
        'photos',
        'extra_data'
    ];

    public function casts()
    {
        return [
            'posted_at' => 'datetime',
            'start_work_on' => 'datetime',
            'end_work_on' => 'datetime',
            'message_id' => 'array',
            'photos' => 'array',
        ];
    }

    public function scopeGetDataForIndex(Builder $query)
    {
        $query
            ->leftJoin('brunches', 'brunches.id', '=', 'reviews.brunch_id')
            ->select(
                'reviews.id AS review_id',
                'posted_at',
                'start_work_on',
                'end_work_on',
                'resource',
                'brunches.name AS brunch_name',
                'total_brunch_rate',
                'score',
                'comment',
                'control_review',
                'final_answer',
                'sender'
            )
        ;
    }

    public function brunch(): BelongsTo
    {
        return $this->belongsTo(Brunch::class);
    }
}
