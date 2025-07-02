<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Brunch extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'user_id',
        'two_gis_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDataForIndex(Builder $query): Builder
    {
        return $query->select(
            'brunches.id AS id',
            'brunches.name AS name',
            'users.name AS upr',
            'brunches.two_gis_id as two_gis_id',
        )->leftJoin('users', 'users.id', '=', 'brunches.user_id');
    }
}
