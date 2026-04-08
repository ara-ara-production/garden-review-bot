<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotInvite extends Model
{
    protected $fillable = [
        'token',
        'driver',
        'bot',
        'user_id',
        'brunch_id',
        'role',
        'assignment',
        'name_hint',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function brunch(): BelongsTo
    {
        return $this->belongsTo(Brunch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAvailable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        return $this->used_count < $this->max_uses;
    }
}
