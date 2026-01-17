<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample teachers if they don't exist
        $teachers = [
            [
                'name' => 'Ms. Sarah Johnson',
                'email' => 'sarah.johnson@school.edu',
                'role' => 'teacher',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Mr. David Chen',
                'email' => 'david.chen@school.edu',
                'role' => 'teacher',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Mrs. Emily Rodriguez',
                'email' => 'emily.rodriguez@school.edu',
                'role' => 'teacher',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($teachers as $teacherData) {
            User::firstOrCreate(
                ['email' => $teacherData['email']],
                $teacherData
            );
        }

        // Get a parent user (assuming one exists)
        $parent = User::where('role', 'parent')->first();
        $teachers = User::where('role', 'teacher')->get();

        if ($parent && $teachers->count() > 0) {
            // Create sample messages from teachers to parent
            $sampleMessages = [
                [
                    'subject' => 'Great Progress in Math',
                    'content' => "Hi there! I wanted to reach out and let you know that your child has been making excellent progress in mathematics this week. They've shown great improvement in solving algebraic equations and have been very engaged during class discussions. Keep up the great work at home!",
                ],
                [
                    'subject' => 'Science Project Update',
                    'content' => "Hello! I'm writing to update you on your child's science project. They've chosen a fascinating topic about renewable energy and have been working diligently on their research. The presentation is scheduled for next Friday, and I'm confident they'll do well.",
                ],
                [
                    'subject' => 'Parent-Teacher Conference Reminder',
                    'content' => "This is a friendly reminder that we have a parent-teacher conference scheduled for next Tuesday at 3:00 PM. Please let me know if you need to reschedule. I'm looking forward to discussing your child's progress and answering any questions you might have.",
                ],
                [
                    'subject' => 'Homework Assignment Notice',
                    'content' => "I wanted to inform you that your child has been consistently completing their homework assignments on time. This is wonderful to see and shows great responsibility. The quality of their work has also improved significantly over the past few weeks.",
                ],
            ];

            foreach ($sampleMessages as $index => $messageData) {
                $teacher = $teachers->random();
                
                Message::create([
                    'sender_id' => $teacher->id,
                    'recipient_id' => $parent->id,
                    'subject' => $messageData['subject'],
                    'content' => $messageData['content'],
                    'is_read' => $index < 2, // First 2 messages are read
                    'read_at' => $index < 2 ? now()->subDays(rand(1, 3)) : null,
                    'created_at' => now()->subDays(rand(1, 7)),
                ]);
            }
        }
    }
}
