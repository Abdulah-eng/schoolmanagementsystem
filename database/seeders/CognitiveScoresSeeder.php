<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CognitiveScore;

class CognitiveScoresSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'alex.johnson@student.com')->first();
        
        if ($user) {
            // Create or update cognitive scores
            CognitiveScore::updateOrCreate(
                ['user_id' => $user->id, 'skill_type' => 'memory'],
                [
                    'current_score' => 45,
                    'highest_score' => 85,
                    'total_sessions' => 3,
                    'average_score' => 28.33,
                ]
            );

            CognitiveScore::updateOrCreate(
                ['user_id' => $user->id, 'skill_type' => 'planning'],
                [
                    'current_score' => 60,
                    'highest_score' => 90,
                    'total_sessions' => 2,
                    'average_score' => 45.00,
                ]
            );

            CognitiveScore::updateOrCreate(
                ['user_id' => $user->id, 'skill_type' => 'flexibility'],
                [
                    'current_score' => 850,
                    'highest_score' => 850,
                    'total_sessions' => 1,
                    'average_score' => 850.00,
                ]
            );

            CognitiveScore::updateOrCreate(
                ['user_id' => $user->id, 'skill_type' => 'creative'],
                [
                    'current_score' => 75,
                    'highest_score' => 75,
                    'total_sessions' => 1,
                    'average_score' => 75.00,
                ]
            );

            $this->command->info('Cognitive scores added for user: ' . $user->name);
        } else {
            $this->command->error('Demo student user not found');
        }
    }
}
