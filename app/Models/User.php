<?php

namespace App\Models;

use App\Casts\UserRoleName;
use App\Dto\User\Factory\ForNotifyDtoFactory;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

/**
 * @method static Builder dataForIndex()
 * @method static Builder byTgUsername(string $username)
 * @method static Builder byVkUserId(string|int $userId)
 * @method static Collection toNotify(null|array $roles = null)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
    #[CodeCoverageIgnore]
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRoleName::class,
        ];
    }

    public function scopeDataForIndex(Builder $query): Builder
    {
        return $query
            ->selectRaw(
                'users.id,
                users.name,
                users.role,
                users.email,
                (select username from user_messenger_accounts where user_messenger_accounts.user_id = users.id and user_messenger_accounts.driver = ?) as telegram_username,
                (select external_id from user_messenger_accounts where user_messenger_accounts.user_id = users.id and user_messenger_accounts.driver = ?) as vk_user_id,
                exists(select 1 from bot_subscriptions where bot_subscriptions.user_id = users.id and bot_subscriptions.driver = ?) as telegram_is_subscribed,
                exists(select 1 from bot_subscriptions where bot_subscriptions.user_id = users.id and bot_subscriptions.driver = ?) as vk_is_subscribed',
                ['telegram', 'vk', 'telegram', 'vk']
            )
            ->orderBy('users.id');
    }

    public function scopeByTgUsername(Builder $query, string $username): Builder
    {
        return $query->whereHas('messengerAccounts', function (Builder $accountQuery) use ($username): void {
            $accountQuery
                ->where('driver', 'telegram')
                ->where('username', $username);
        });
    }

    public function scopeByVkUserId(Builder $query, string|int $userId): Builder
    {
        return $query->whereHas('messengerAccounts', function (Builder $accountQuery) use ($userId): void {
            $accountQuery
                ->where('driver', 'vk')
                ->where('external_id', (string) $userId);
        });
    }

    public function scopeToNotify(Builder $query, ?array $roles = null): Collection
    {
        return $query->select('users.id', 'users.role')
            ->whereIn('role', $roles ?? UserRoleEnum::toArray()->pluck('name'))
            ->get()
            ->map(fn (User $user) => app(ForNotifyDtoFactory::class)->fromEntity($user));
    }

    public function brunches()
    {
        return $this->hasMany(Brunch::class);
    }

    public function brunchesPupr()
    {
        return $this->hasMany(Brunch::class, 'pupr_user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(BotMessage::class);
    }

    public function messengerAccounts(): HasMany
    {
        return $this->hasMany(UserMessengerAccount::class);
    }

    public function telegramAccount(): HasOne
    {
        return $this->hasOne(UserMessengerAccount::class)->where('driver', 'telegram');
    }

    public function vkAccount(): HasOne
    {
        return $this->hasOne(UserMessengerAccount::class)->where('driver', 'vk');
    }

    public function botSubscriptions(): HasMany
    {
        return $this->hasMany(BotSubscription::class);
    }
}
