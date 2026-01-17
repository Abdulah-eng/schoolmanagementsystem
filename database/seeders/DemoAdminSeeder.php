<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@edufocus.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+1-555-0001',
            'address' => '456 Admin Avenue, Management City, MC 54321',
        ]);

        $this->command->info('Demo admin user created successfully!');
        $this->command->info('Email: admin@edufocus.com');
        $this->command->info('Password: admin123');
    }
}
