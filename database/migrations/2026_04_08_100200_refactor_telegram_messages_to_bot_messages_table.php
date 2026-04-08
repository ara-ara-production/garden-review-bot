<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('telegram_messages') && ! Schema::hasTable('bot_messages')) {
            Schema::rename('telegram_messages', 'bot_messages');
        }

        Schema::table('bot_messages', function (Blueprint $table): void {
            $table->string('driver')->default('telegram')->after('user_id');
            $table->string('bot')->default(config('telegram.default', 'mybot'))->after('driver');
            $table->string('recipient_id')->nullable()->after('bot');
        });

        if (Schema::hasColumn('users', 'telegram_chat')) {
            DB::table('bot_messages')
                ->select('id', 'user_id')
                ->whereNull('recipient_id')
                ->orderBy('id')
                ->get()
                ->each(function (object $message): void {
                    $recipientId = DB::table('users')
                        ->where('id', $message->user_id)
                        ->value('telegram_chat');

                    DB::table('bot_messages')
                        ->where('id', $message->id)
                        ->update([
                            'driver' => 'telegram',
                            'bot' => config('telegram.default', 'mybot'),
                            'recipient_id' => $recipientId,
                        ]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('bot_messages', function (Blueprint $table): void {
            $table->dropColumn(['driver', 'bot', 'recipient_id']);
        });

        if (Schema::hasTable('bot_messages') && ! Schema::hasTable('telegram_messages')) {
            Schema::rename('bot_messages', 'telegram_messages');
        }
    }
};
