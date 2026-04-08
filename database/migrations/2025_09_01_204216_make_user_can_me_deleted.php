<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = Schema::hasTable('bot_messages') ? 'bot_messages' : 'telegram_messages';

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table($tableName, function (Blueprint $table): void {
            $table->dropForeign(['user_id']);
        });

        Schema::table($tableName, function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
