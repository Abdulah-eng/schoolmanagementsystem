<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed demo users and data
        // Order matters: create parents before students so parent_id is set
        $this->call([
            DemoParentSeeder::class,
            DemoStudentSeeder::class,
            DemoAdminSeeder::class,
            CourseSeeder::class,
            StudentCourseEnrollmentSeeder::class,
        ]);
    }
}
