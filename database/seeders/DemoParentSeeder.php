<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoParentSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'parent@edufocus.com'],
            [
                'name' => 'Parent',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'phone' => '555-0100',
                'address' => '123 Parent Street',
            ]
        );
    }
}


