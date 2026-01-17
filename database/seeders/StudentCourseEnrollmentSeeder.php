<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use App\Models\Assignment;
use Illuminate\Support\Facades\Hash;

class StudentCourseEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teacher
        $teacher = User::where('role', 'teacher')->first();
        
        if (!$teacher) {
            $this->command->warn('No teacher found. Please run CourseSeeder first.');
            return;
        }

        // Get all courses
        $courses = Course::where('teacher_id', $teacher->id)->get();
        
        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please run CourseSeeder first.');
            return;
        }

        // Get existing student or create additional demo students
        $students = Student::with('user')->get();
        
        // Create additional demo students if we only have one
        if ($students->count() < 5) {
            $studentData = [
                [
                    'name' => 'Emma Wilson',
                    'email' => 'emma.wilson@student.com',
                    'grade_level' => 'Grade 9',
                    'section' => 'B',
                ],
                [
                    'name' => 'Michael Chen',
                    'email' => 'michael.chen@student.com',
                    'grade_level' => 'Grade 8',
                    'section' => 'A',
                ],
                [
                    'name' => 'Sophia Martinez',
                    'email' => 'sophia.martinez@student.com',
                    'grade_level' => 'Grade 10',
                    'section' => 'C',
                ],
                [
                    'name' => 'James Brown',
                    'email' => 'james.brown@student.com',
                    'grade_level' => 'Grade 9',
                    'section' => 'B',
                ],
                [
                    'name' => 'Olivia Davis',
                    'email' => 'olivia.davis@student.com',
                    'grade_level' => 'Grade 8',
                    'section' => 'A',
                ],
            ];

            foreach ($studentData as $data) {
                $user = User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make('password123'),
                        'role' => 'student',
                        'phone' => '+1-555-' . rand(1000, 9999),
                        'address' => 'Student Address',
                    ]
                );

                // Check if student already exists
                $student = Student::where('user_id', $user->id)->first();
                
                if (!$student) {
                    // Generate unique student_id with a counter approach
                    $year = date('Y');
                    $maxSequence = Student::where('student_id', 'like', $year . '%')
                        ->get()
                        ->map(function($s) use ($year) {
                            return intval(substr($s->student_id, -4));
                        })
                        ->max() ?? 0;
                    
                    $studentId = $year . str_pad($maxSequence + 1, 4, '0', STR_PAD_LEFT);
                    
                    // Ensure uniqueness
                    $counter = 1;
                    while (Student::where('student_id', $studentId)->exists()) {
                        $studentId = $year . str_pad($maxSequence + $counter, 4, '0', STR_PAD_LEFT);
                        $counter++;
                    }
                    
                    Student::create([
                        'user_id' => $user->id,
                        'student_id' => $studentId,
                        'grade_level' => $data['grade_level'],
                        'section' => $data['section'],
                        'enrollment_date' => now()->subMonths(rand(1, 12)),
                        'parent_name' => $data['name'] . ' Parent',
                        'parent_phone' => '+1-555-' . rand(1000, 9999),
                        'medical_info' => 'No known allergies.',
                    ]);
                }
            }
            
            // Refresh students collection
            $students = Student::with('user')->get();
        }

        // Enroll students in courses
        $enrollmentCount = 0;
        foreach ($students as $student) {
            // Each student enrolled in 2-4 random courses
            $coursesToEnroll = $courses->random(rand(2, min(4, $courses->count())));
            
            foreach ($coursesToEnroll as $course) {
                // Check if already enrolled
                $exists = \DB::table('student_course')
                    ->where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();
                
                if (!$exists) {
                    \DB::table('student_course')->insert([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'status' => 'enrolled',
                        'progress' => rand(0, 100),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $enrollmentCount++;
                }
            }
        }

        $this->command->info("Enrolled {$enrollmentCount} student-course relationships.");

        // Create sample assignments for courses
        $assignmentCount = 0;
        foreach ($courses as $course) {
            // Create 2-3 assignments per course
            $assignmentTypes = ['homework', 'project', 'quiz', 'exam'];
            
            for ($i = 0; $i < rand(2, 3); $i++) {
                $dueDate = now()->addDays(rand(1, 30));
                
                Assignment::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'title' => $course->course_name . ' - ' . ucfirst($assignmentTypes[array_rand($assignmentTypes)]) . ' ' . ($i + 1),
                    ],
                    [
                        'description' => 'Complete this assignment for ' . $course->course_name . '. Submit your work by the due date.',
                        'due_date' => $dueDate,
                        'max_points' => rand(50, 100),
                        'assignment_type' => $assignmentTypes[array_rand($assignmentTypes)],
                        'is_published' => true,
                    ]
                );
                $assignmentCount++;
            }
        }

        $this->command->info("Created {$assignmentCount} assignments across all courses.");
        $this->command->info('Student-course enrollment completed successfully!');
    }
}
