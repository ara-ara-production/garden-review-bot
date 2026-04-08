<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('driver');
            $table->string('bot');
            $table->string('recipient_id');
            $table->json('meta')->nullable();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'driver', 'bot']);
            $table->index(['driver', 'bot']);
        });

        if (! Schema::hasColumn('users', 'telegram_chat')) {
            return;
        }

        $defaultBot = config('telegram.default', 'mybot');
        $now = now();

        $telegramSubscriptions = DB::table('users')
            ->select('id as user_id', 'telegram_chat')
            ->whereNotNull('telegram_chat')
            ->get()
            ->map(function (object $user) use ($defaultBot, $now): array {
                return [
                    'user_id' => $user->user_id,
                    'driver' => 'telegram',
                    'bot' => $defaultBot,
                    'recipient_id' => $user->telegram_chat,
                    'meta' => null,
                    'subscribed_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->all();

        if ($telegramSubscriptions !== []) {
            DB::table('bot_subscriptions')->insert($telegramSubscriptions);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_subscriptions');
    }
};
