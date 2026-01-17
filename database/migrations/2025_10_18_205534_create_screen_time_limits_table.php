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
        Schema::create('screen_time_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('daily_limit_minutes')->default(120);
            $table->integer('weekday_limit_minutes')->default(120);
            $table->integer('weekend_limit_minutes')->default(180);
            $table->integer('bedtime_hour')->default(21);
            $table->integer('wakeup_hour')->default(7);
            $table->json('blocked_apps')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_time_limits');
    }
};
