<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class DemoStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get parent user
        $parent = User::where('email', 'parent@edufocus.com')->first();
        
        // Create or update demo student user
        $user = User::updateOrCreate(
            ['email' => 'alex.johnson@student.com'],
            [
                'name' => 'Alex Johnson',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'phone' => '+1-555-0123',
                'address' => '123 Student Street, Learning City, LC 12345',
                'parent_id' => $parent ? $parent->id : null,
            ]
        );

        // Create or update student record
        Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'student_id' => Student::generateStudentId(),
                'grade_level' => 'Grade 8',
                'section' => 'A',
                'enrollment_date' => now()->subMonths(6),
                'parent_name' => 'Sarah Johnson',
                'parent_phone' => '+1-555-0124',
                'medical_info' => 'No known allergies or medical conditions.',
            ]
        );

        // Create or update initial cognitive scores
        \App\Models\CognitiveScore::updateOrCreate(
            ['user_id' => $user->id, 'skill_type' => 'memory'],
            [
                'current_score' => 45,
                'highest_score' => 85,
                'total_sessions' => 3,
                'average_score' => 28.33,
            ]
        );

        \App\Models\CognitiveScore::updateOrCreate(
            ['user_id' => $user->id, 'skill_type' => 'planning'],
            [
                'current_score' => 60,
                'highest_score' => 90,
                'total_sessions' => 2,
                'average_score' => 45.00,
            ]
        );

        \App\Models\CognitiveScore::updateOrCreate(
            ['user_id' => $user->id, 'skill_type' => 'flexibility'],
            [
                'current_score' => 850,
                'highest_score' => 850,
                'total_sessions' => 1,
                'average_score' => 850.00,
            ]
        );

        \App\Models\CognitiveScore::updateOrCreate(
            ['user_id' => $user->id, 'skill_type' => 'creative'],
            [
                'current_score' => 75,
                'highest_score' => 75,
                'total_sessions' => 1,
                'average_score' => 75.00,
            ]
        );

        $this->command->info('Demo student user created successfully!');
        $this->command->info('Email: alex.johnson@student.com');
        $this->command->info('Password: password123');
    }
}
