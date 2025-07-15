<?php

namespace App\Models;

use App\Casts\UserRoleName;
use App\Dto\User\Factory\ForNotifyDtoFactory;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

/**
 * @method static Builder dataForIndex()
 * @method static Builder byTgUsername(string $username)
 * @method static Collection toNotify(null|array $roles = null)
 * @method static Collection chatIdByRole(array $roles)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_username',
        'telegram_chat',
        'role',
        'pupr_user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[CodeCoverageIgnore] protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_subscribed' => 'boolean',
            'role' => UserRoleName::class,
        ];
    }

    public function scopeDataForIndex(Builder $query): Builder
    {
        return $query->selectRaw(
            'id,
            name,
            telegram_username,
            role,
            (telegram_chat IS NOT NULL) AS "is_subscribed"'
        );
    }

    public function scopeByTgUsername(Builder $query, string $username): Builder
    {
        return $query->where('telegram_username', $username);
    }

    public function scopeToNotify(Builder $query, ?array $roles = null): Collection
    {
        return $query->select('telegram_chat', 'id', 'role')
            ->whereIn('role', $roles ?? UserRoleEnum::toArray()->pluck('name'))
            ->whereNotNull('telegram_chat')
            ->get()
            ->map(fn (User $user) => app(ForNotifyDtoFactory::class)->fromEntity($user));
    }

    public function brunches()
    {
        return $this->hasMany(Brunch::class);
    }

    public function brunchesPupr()
    {
        return $this->hasMany(Brunch::class, 'pupr_user_id','id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TelegramMessage::class);
    }
}
