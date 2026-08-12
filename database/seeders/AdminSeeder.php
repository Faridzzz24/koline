<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin System
        User::updateOrCreate(
            ['email' => 'admin@koline.test'],
            [
                'name' => 'Admin System',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'gender' => 'male',
                'email_verified_at' => now(),
            ]
        );
    }
}
