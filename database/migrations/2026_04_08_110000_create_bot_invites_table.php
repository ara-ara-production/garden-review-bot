<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_invites', function (Blueprint $table): void {
            $table->id();
            $table->string('token')->unique();
            $table->string('driver');
            $table->string('bot');
            $table->foreignId('brunch_id')->nullable()->constrained('brunches')->nullOnDelete();
            $table->string('role');
            $table->string('assignment')->nullable();
            $table->string('name_hint')->nullable();
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_invites');
    }
};
