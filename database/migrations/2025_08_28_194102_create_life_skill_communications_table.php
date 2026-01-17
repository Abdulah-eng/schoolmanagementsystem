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
        Schema::create('life_skill_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('scenario_type', ['group-project', 'teacher-meeting', 'parent-conversation']);
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->text('reflection')->nullable();
            $table->integer('confidence_rating')->nullable(); // 1-5 scale
            $table->integer('time_spent')->nullable(); // in minutes
            $table->timestamps();
            
            $table->index(['user_id', 'scenario_type']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('life_skill_communications');
    }
};
