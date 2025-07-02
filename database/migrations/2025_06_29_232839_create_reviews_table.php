<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key')->unique(true)->nullable(false);
            $table->string('resource')->nullable();
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('brunch_id')->nullable()->constrained('brunches')->nullOnDelete();
            $table->integer('score')->nullable();
            $table->text('comment')->nullable();
            $table->string('sender')->nullable();
            $table->string('link')->nullable();
            $table->dateTime('start_work_on')->nullable();
            $table->dateTime('end_work_on')->nullable();
            $table->text('control_review')->nullable();
            $table->text('final_answer')->nullable();
            $table->json('message_id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
