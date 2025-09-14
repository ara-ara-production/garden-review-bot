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
        'two_gis_id',
        'pupr_user_id',
        'address'
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
            'upr_user.name AS upr',
            'pupr_user.name AS pupr',
            'brunches.address as address',
            'brunches.two_gis_id as two_gis_id',
        )->leftJoin('users as upr_user', 'upr_user.id', '=', 'brunches.user_id')
            ->leftJoin('users as pupr_user', 'pupr_user.id', '=', 'brunches.pupr_user_id');
    }

    public function scopeDataForFilter(Builder $query): Builder
    {
        return $query->select('id', 'name');
    }
}
