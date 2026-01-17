<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\FocusSession;
use App\Models\CognitiveSession;
use App\Models\User;

class StudentActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::with('user')->get();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found.');
            return;
        }

        $focusSessionCount = 0;
        $cognitiveSessionCount = 0;

        foreach ($students as $student) {
            $userId = $student->user_id;
            
            // Create focus sessions for the past 7 days
            for ($i = 0; $i < rand(3, 8); $i++) {
                $daysAgo = rand(0, 7);
                $sessionTypes = ['pomodoro', 'deep_work', 'quick_focus'];
                $startedAt = now()->subDays($daysAgo)->subHours(rand(0, 23));
                $elapsedSeconds = rand(600, 3600); // 10 minutes to 1 hour
                
                FocusSession::create([
                    'user_id' => $userId,
                    'session_type' => $sessionTypes[array_rand($sessionTypes)],
                    'planned_minutes' => rand(20, 60),
                    'elapsed_seconds' => $elapsedSeconds,
                    'status' => 'completed',
                    'started_at' => $startedAt,
                    'completed_at' => $startedAt->copy()->addSeconds($elapsedSeconds),
                    'settings' => [],
                ]);
                $focusSessionCount++;
            }

            // Create cognitive sessions for the past 7 days
            for ($i = 0; $i < rand(2, 5); $i++) {
                $daysAgo = rand(0, 7);
                $skillTypes = ['memory', 'planning', 'flexibility', 'creative'];
                $startedAt = now()->subDays($daysAgo)->subHours(rand(0, 23));
                $timeTaken = rand(300, 1800); // 5 to 30 minutes
                
                CognitiveSession::create([
                    'user_id' => $userId,
                    'skill_type' => $skillTypes[array_rand($skillTypes)],
                    'difficulty_level' => rand(1, 5),
                    'status' => 'completed',
                    'score' => rand(40, 100),
                    'is_correct' => rand(0, 1) == 1,
                    'time_taken' => $timeTaken,
                    'started_at' => $startedAt,
                    'completed_at' => $startedAt->copy()->addSeconds($timeTaken),
                ]);
                $cognitiveSessionCount++;
            }
        }

        $this->command->info("Created {$focusSessionCount} focus sessions.");
        $this->command->info("Created {$cognitiveSessionCount} cognitive sessions.");
        $this->command->info('Student activity seeding completed!');
    }
}
