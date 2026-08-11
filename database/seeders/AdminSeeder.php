<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@koline.test'],
            [
                'name' => 'Admin KoLine',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'gender' => 'male',
                'email_verified_at' => now(),
            ]
        );

        // Sample patient 1
        User::updateOrCreate(
            ['email' => 'pasien@koline.test'],
            [
                'name' => 'Budi Pasien',
                'password' => Hash::make('pasien123'),
                'role' => 'patient',
                'phone' => '087654321098',
                'gender' => 'male',
                'date_of_birth' => '1995-05-15',
                'blood_type' => 'A',
                'address' => 'Jl. Sudirman No. 10, Jakarta Pusat',
                'email_verified_at' => now(),
            ]
        );

        // Sample patient 2
        User::updateOrCreate(
            ['email' => 'sari@koline.test'],
            [
                'name' => 'Sari Wulandari',
                'password' => Hash::make('pasien123'),
                'role' => 'patient',
                'phone' => '082109876543',
                'gender' => 'female',
                'date_of_birth' => '1990-12-20',
                'blood_type' => 'O',
                'address' => 'Jl. Thamrin No. 5, Jakarta Selatan',
                'email_verified_at' => now(),
            ]
        );
    }
}
