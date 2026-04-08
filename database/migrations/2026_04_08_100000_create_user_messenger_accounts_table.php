<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_messenger_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('driver');
            $table->string('username')->nullable();
            $table->string('external_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'driver']);
        });

        $now = now();

        $telegramAccounts = DB::table('users')
            ->select('id as user_id', 'telegram_username', 'telegram_chat')
            ->where(function ($query): void {
                $query
                    ->whereNotNull('telegram_username')
                    ->orWhereNotNull('telegram_chat');
            })
            ->get()
            ->map(function (object $user) use ($now): array {
                return [
                    'user_id' => $user->user_id,
                    'driver' => 'telegram',
                    'username' => $user->telegram_username,
                    'external_id' => $user->telegram_chat,
                    'meta' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->all();

        if ($telegramAccounts !== []) {
            DB::table('user_messenger_accounts')->insert($telegramAccounts);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_messenger_accounts');
    }
};
