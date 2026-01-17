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
        Schema::create('breathing_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('cycles')->default(5); // number of breaths
            $table->unsignedSmallInteger('inhale_seconds')->default(4);
            $table->unsignedSmallInteger('hold_seconds')->default(4);
            $table->unsignedSmallInteger('exhale_seconds')->default(6);
            $table->enum('status', ['running','completed','cancelled'])->default('running');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breathing_sessions');
    }
};
