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
        Schema::create('cognitive_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('skill_type', ['memory', 'planning', 'flexibility', 'creative']);
            $table->integer('current_score')->default(0);
            $table->integer('highest_score')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->decimal('average_score', 5, 2)->default(0.00);
            $table->timestamps();
            
            $table->unique(['user_id', 'skill_type']);
            $table->index(['skill_type', 'current_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cognitive_scores');
    }
};
