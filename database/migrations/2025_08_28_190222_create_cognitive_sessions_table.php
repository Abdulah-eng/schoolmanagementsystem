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
        Schema::create('cognitive_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('skill_type', ['memory', 'planning', 'flexibility', 'creative']);
            $table->integer('difficulty_level')->default(1);
            $table->enum('status', ['active', 'completed', 'abandoned'])->default('active');
            $table->integer('score')->default(0);
            $table->boolean('is_correct')->default(false);
            $table->integer('time_taken')->nullable(); // in seconds
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'skill_type']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cognitive_sessions');
    }
};
