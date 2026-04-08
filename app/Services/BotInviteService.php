<?php

namespace App\Services;

use App\Bot\Data\BotDefinition;
use App\Bot\Data\IncomingBotUpdate;
use App\Bot\Services\BotRegistry;
use App\Enums\UserRoleEnum;
use App\Models\BotInvite;
use App\Models\BotSubscription;
use App\Models\Brunch;
use App\Models\User;
use App\Models\UserMessengerAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class BotInviteService
{
    public function __construct(
        protected BotRegistry $botRegistry,
    ) {}

    public function create(array $data): BotInvite
    {
        $bot = $this->botRegistry->find($data['bot']);

        if ($bot->driver !== $data['driver']) {
            throw new RuntimeException('Выбранный бот не соответствует платформе.');
        }

        if (($data['user_id'] ?? null) !== null && $data['user_id'] !== '' && $data['user_id'] !== '__new__') {
            User::query()->findOrFail((int) $data['user_id']);
        }

        if (! empty($data['assignment']) && empty($data['brunch_id'])) {
            throw new RuntimeException('Для назначения в филиал нужно выбрать филиал.');
        }

        if (($data['user_id'] ?? null) === '__new__' && blank($data['name_hint'] ?? null)) {
            throw new RuntimeException('Для нового пользователя нужно указать имя.');
        }

        return BotInvite::create([
            'token' => Str::random(32),
            'driver' => $data['driver'],
            'bot' => $data['bot'],
            'user_id' => ($data['user_id'] ?? null) && $data['user_id'] !== '__new__' ? (int) $data['user_id'] : null,
            'brunch_id' => $data['brunch_id'] ?: null,
            'role' => $data['role'],
            'assignment' => $data['assignment'] ?: null,
            'name_hint' => $data['name_hint'] ?: null,
            'max_uses' => $data['max_uses'],
            'expires_at' => $data['expires_at'] ?: null,
            'meta' => null,
        ]);
    }

    public function buildLink(BotInvite $invite): string
    {
        $bot = $this->botRegistry->find($invite->bot);

        return match ($invite->driver) {
            'telegram' => $this->telegramLink($bot, $invite->token),
            'vk' => $this->vkLink($bot, $invite->token),
            default => throw new RuntimeException('Unsupported invite driver.'),
        };
    }

    public function claim(string $token, IncomingBotUpdate $update): User
    {
        /** @var BotInvite $invite */
        $invite = BotInvite::query()->where('token', $token)->firstOrFail();

        if (! $invite->isAvailable()) {
            throw new RuntimeException('Ссылка приглашения больше не активна.');
        }

        return DB::transaction(function () use ($invite, $update): User {
            $user = $this->resolveOrCreateUser($invite, $update);

            $this->syncMessengerAccount($user, $invite->driver, $update);
            $this->syncSubscription($user, $invite, $update);
            $this->applyRoleAndBranch($user, $invite);

            $invite->used_count++;
            $invite->is_active = $invite->used_count < $invite->max_uses;
            $invite->save();

            return $user->refresh();
        });
    }

    /**
     * @return array<int, array{name:string,value:string}>
     */
    public function botOptions(): array
    {
        return collect($this->botRegistry->allEnabled())
            ->map(fn (BotDefinition $bot): array => [
                'name' => $bot->name,
                'value' => "{$bot->driver}: {$bot->name}",
            ])
            ->values()
            ->all();
    }

    protected function resolveOrCreateUser(BotInvite $invite, IncomingBotUpdate $update): User
    {
        if ($invite->user_id !== null) {
            return User::query()->findOrFail($invite->user_id);
        }

        $existingByExternalId = UserMessengerAccount::query()
            ->where('driver', $invite->driver)
            ->where('external_id', $this->externalIdForDriver($invite->driver, $update))
            ->first();

        if ($existingByExternalId instanceof UserMessengerAccount) {
            return $existingByExternalId->user;
        }

        if ($invite->driver === 'telegram' && $update->senderUsername) {
            $existingByUsername = UserMessengerAccount::query()
                ->where('driver', 'telegram')
                ->where('username', $update->senderUsername)
                ->first();

            if ($existingByUsername instanceof UserMessengerAccount) {
                return $existingByUsername->user;
            }
        }

        return User::create([
            'name' => $invite->name_hint ?: $this->fallbackUserName($invite->driver, $update),
            'email' => null,
            'password' => null,
            'role' => UserRoleEnum::tryFromName($invite->role),
        ]);
    }

    protected function syncMessengerAccount(User $user, string $driver, IncomingBotUpdate $update): void
    {
        UserMessengerAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'driver' => $driver,
            ],
            [
                'username' => $driver === 'telegram' ? $update->senderUsername : null,
                'external_id' => $this->externalIdForDriver($driver, $update),
            ],
        );
    }

    protected function syncSubscription(User $user, BotInvite $invite, IncomingBotUpdate $update): void
    {
        BotSubscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'driver' => $invite->driver,
                'bot' => $invite->bot,
            ],
            [
                'recipient_id' => $update->recipientId,
                'subscribed_at' => now(),
            ],
        );
    }

    protected function applyRoleAndBranch(User $user, BotInvite $invite): void
    {
        $user->update([
            'role' => UserRoleEnum::tryFromName($invite->role),
        ]);

        if ($invite->brunch_id === null || $invite->assignment === null) {
            return;
        }

        Brunch::query()
            ->whereKey($invite->brunch_id)
            ->update([
                $invite->assignment => $user->id,
            ]);
    }

    protected function telegramLink(BotDefinition $bot, string $token): string
    {
        $username = $bot->config['username'] ?? null;

        if (! is_string($username) || $username === '') {
            throw new RuntimeException('Для Telegram-бота не задан username в конфиге.');
        }

        return "https://t.me/{$username}?start={$token}";
    }

    protected function vkLink(BotDefinition $bot, string $token): string
    {
        $screenName = $bot->config['screen_name'] ?? null;
        $groupId = $bot->config['group_id'] ?? null;

        if (is_string($screenName) && $screenName !== '') {
            return "https://vk.me/{$screenName}?ref={$token}";
        }

        if ($groupId) {
            return "https://vk.me/public{$groupId}?ref={$token}";
        }

        throw new RuntimeException('Для VK-бота не задан screen_name или group_id в конфиге.');
    }

    protected function externalIdForDriver(string $driver, IncomingBotUpdate $update): string
    {
        return match ($driver) {
            'telegram' => $update->recipientId,
            'vk' => (string) ($update->senderId ?: $update->recipientId),
            default => $update->recipientId,
        };
    }

    protected function fallbackUserName(string $driver, IncomingBotUpdate $update): string
    {
        return match ($driver) {
            'telegram' => $update->senderUsername ? "@{$update->senderUsername}" : "Telegram {$update->recipientId}",
            'vk' => "VK {$update->senderId}",
            default => "User {$update->recipientId}",
        };
    }
}
