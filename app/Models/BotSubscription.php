<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'driver',
        'bot',
        'recipient_id',
        'meta',
        'subscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'subscribed_at' => 'datetime',
        ];
    }

    public function scopeForBot(Builder $query, string $driver, string $bot): Builder
    {
        return $query
            ->where('driver', $driver)
            ->where('bot', $bot);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
