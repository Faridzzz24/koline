<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SpecializationSeeder::class,
            DoctorSeeder::class,
            MedicineSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
