<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bot_invites', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('bot')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bot_invites', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
