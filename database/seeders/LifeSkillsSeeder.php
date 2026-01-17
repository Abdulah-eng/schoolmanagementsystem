<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LifeSkillSchedule;
use App\Models\LifeSkillRoutine;
use App\Models\LifeSkillBudget;

class LifeSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::where('email', 'alex.johnson@student.com')->first();
        
        if (!$student) {
            $this->command->error('Demo student not found. Please run DemoStudentSeeder first.');
            return;
        }

        // Add sample schedule events
        LifeSkillSchedule::create([
            'user_id' => $student->id,
            'name' => 'Math Study',
            'day' => 'monday',
            'start_time' => '16:00:00',
            'duration' => 60,
        ]);

        LifeSkillSchedule::create([
            'user_id' => $student->id,
            'name' => 'Science Project',
            'day' => 'monday',
            'start_time' => '17:00:00',
            'duration' => 90,
        ]);

        LifeSkillSchedule::create([
            'user_id' => $student->id,
            'name' => 'English Reading',
            'day' => 'tuesday',
            'start_time' => '16:00:00',
            'duration' => 45,
        ]);

        LifeSkillSchedule::create([
            'user_id' => $student->id,
            'name' => 'History Research',
            'day' => 'wednesday',
            'start_time' => '16:00:00',
            'duration' => 60,
        ]);

        // Add sample daily routine
        LifeSkillRoutine::create([
            'user_id' => $student->id,
            'activity' => 'Morning Exercise',
            'time' => '07:00:00',
            'duration' => 30,
        ]);

        LifeSkillRoutine::create([
            'user_id' => $student->id,
            'activity' => 'Homework Time',
            'time' => '18:00:00',
            'duration' => 90,
        ]);

        LifeSkillRoutine::create([
            'user_id' => $student->id,
            'activity' => 'Reading',
            'time' => '20:00:00',
            'duration' => 30,
        ]);

        // Add initial budget data
        LifeSkillBudget::create([
            'user_id' => $student->id,
            'monthly_allowance' => 50.00,
            'savings_goal' => 200.00,
            'current_savings' => 25.00,
            'total_expenses' => 0.00,
        ]);

        $this->command->info('Life Skills data seeded successfully for demo student!');
    }
}
