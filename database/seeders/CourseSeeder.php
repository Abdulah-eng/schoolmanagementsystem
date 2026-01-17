<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Get a teacher user
        $teacher = User::where('role', 'teacher')->first();
        
        if (!$teacher) {
            // Create a teacher if none exists
            $teacher = User::create([
                'name' => 'Demo Teacher',
                'email' => 'teacher@edufocus.com',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'phone' => '+1-555-0002',
                'address' => '789 Teacher Street, Education City, EC 67890',
            ]);
        }

        $courses = [
            [
                'course_code' => 'MATH101',
                'course_name' => 'Mathematics',
                'description' => 'Algebra, Geometry, Calculus',
                'grade_level' => 'Grade 8-12',
                'credits' => 4,
                'teacher_id' => $teacher->id,
                'is_active' => true,
            ],
            [
                'course_code' => 'SCI101',
                'course_name' => 'Science',
                'description' => 'Physics, Chemistry, Biology',
                'grade_level' => 'Grade 8-12',
                'credits' => 4,
                'teacher_id' => $teacher->id,
                'is_active' => true,
            ],
            [
                'course_code' => 'LANG101',
                'course_name' => 'Languages',
                'description' => 'English, Spanish, French',
                'grade_level' => 'Grade 8-12',
                'credits' => 3,
                'teacher_id' => $teacher->id,
                'is_active' => true,
            ],
            [
                'course_code' => 'HIST101',
                'course_name' => 'History',
                'description' => 'World History, Geography, Social Studies',
                'grade_level' => 'Grade 8-12',
                'credits' => 3,
                'teacher_id' => $teacher->id,
                'is_active' => true,
            ],
            [
                'course_code' => 'ART101',
                'course_name' => 'Arts',
                'description' => 'Visual Arts, Music, Drama',
                'grade_level' => 'Grade 8-12',
                'credits' => 2,
                'teacher_id' => $teacher->id,
                'is_active' => true,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::updateOrCreate(
                ['course_code' => $courseData['course_code']],
                $courseData
            );
        }

        $this->command->info('Courses seeded successfully!');
    }
}
