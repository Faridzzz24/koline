<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            ['name' => 'Dokter Umum', 'icon' => '🩺', 'color' => '#10B981', 'description' => 'Penanganan kesehatan umum dan penyakit ringan'],
            ['name' => 'Spesialis Penyakit Dalam', 'icon' => '🫀', 'color' => '#EF4444', 'description' => 'Diagnosis dan pengobatan penyakit dalam'],
            ['name' => 'Spesialis Anak', 'icon' => '👶', 'color' => '#F59E0B', 'description' => 'Kesehatan bayi, anak, dan remaja'],
            ['name' => 'Spesialis Kulit', 'icon' => '🧴', 'color' => '#8B5CF6', 'description' => 'Perawatan kulit, rambut, dan kuku'],
            ['name' => 'Spesialis Kandungan', 'icon' => '🤰', 'color' => '#EC4899', 'description' => 'Kesehatan reproduksi wanita dan kehamilan'],
            ['name' => 'Spesialis Jantung', 'icon' => '❤️', 'color' => '#DC2626', 'description' => 'Penyakit dan gangguan jantung'],
            ['name' => 'Psikiater', 'icon' => '🧠', 'color' => '#6366F1', 'description' => 'Kesehatan mental dan gangguan psikiatri'],
            ['name' => 'Spesialis Gigi', 'icon' => '🦷', 'color' => '#06B6D4', 'description' => 'Kesehatan gigi dan mulut'],
            ['name' => 'Spesialis Mata', 'icon' => '👁️', 'color' => '#3B82F6', 'description' => 'Gangguan dan penyakit mata'],
            ['name' => 'Spesialis THT', 'icon' => '👂', 'color' => '#14B8A6', 'description' => 'Telinga, hidung, dan tenggorokan'],
            ['name' => 'Ahli Gizi', 'icon' => '🥗', 'color' => '#84CC16', 'description' => 'Konsultasi gizi dan pola makan sehat'],
            ['name' => 'Psikolog', 'icon' => '💭', 'color' => '#A855F7', 'description' => 'Konseling psikologi dan terapi'],
        ];

        foreach ($specializations as $spec) {
            Specialization::create([
                'name' => $spec['name'],
                'slug' => Str::slug($spec['name']),
                'description' => $spec['description'],
                'icon' => $spec['icon'],
                'color' => $spec['color'],
                'is_active' => true,
            ]);
        }
    }
}
