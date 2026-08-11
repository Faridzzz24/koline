<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dr. Andi Wijaya, Sp.PD',
                'email' => 'andi.wijaya@koline.test',
                'specialization' => 'Spesialis Penyakit Dalam',
                'str' => 'STR-001-2024',
                'experience' => 12,
                'fee' => 75000,
                'rating' => 4.9,
                'hospital' => 'RS Pusat Pertamina',
                'education' => 'Universitas Indonesia',
                'bio' => 'Dokter spesialis penyakit dalam dengan pengalaman 12 tahun. Berpengalaman menangani diabetes, hipertensi, dan penyakit metabolik.',
                'patients' => 2450,
                'reviews' => 340,
            ],
            [
                'name' => 'Dr. Siti Rahayu, Sp.A',
                'email' => 'siti.rahayu@koline.test',
                'specialization' => 'Spesialis Anak',
                'str' => 'STR-002-2024',
                'experience' => 8,
                'fee' => 65000,
                'rating' => 4.8,
                'hospital' => 'RS Harapan Bunda',
                'education' => 'Universitas Gadjah Mada',
                'bio' => 'Dokter spesialis anak yang berdedikasi dalam tumbuh kembang anak. Ahli imunisasi dan nutrisi anak.',
                'patients' => 1890,
                'reviews' => 280,
            ],
            [
                'name' => 'Dr. Budi Santoso, Sp.JP',
                'email' => 'budi.santoso@koline.test',
                'specialization' => 'Spesialis Jantung',
                'str' => 'STR-003-2024',
                'experience' => 15,
                'fee' => 100000,
                'rating' => 4.9,
                'hospital' => 'RS Jantung Harapan Kita',
                'education' => 'Universitas Airlangga',
                'bio' => 'Kardiolog berpengalaman 15 tahun. Spesialis intervensi koroner dan elektrofisiologi jantung.',
                'patients' => 3100,
                'reviews' => 420,
            ],
            [
                'name' => 'Dr. Maya Putri, Sp.KK',
                'email' => 'maya.putri@koline.test',
                'specialization' => 'Spesialis Kulit',
                'str' => 'STR-004-2024',
                'experience' => 7,
                'fee' => 70000,
                'rating' => 4.7,
                'hospital' => 'Klinik Estetika Glowing',
                'education' => 'Universitas Padjadjaran',
                'bio' => 'Dokter dermatologi dengan keahlian di bidang estetika dan penyakit kulit. Ahli dalam perawatan jerawat dan anti-aging.',
                'patients' => 1560,
                'reviews' => 210,
            ],
            [
                'name' => 'Dr. Rina Kusuma, Sp.OG',
                'email' => 'rina.kusuma@koline.test',
                'specialization' => 'Spesialis Kandungan',
                'str' => 'STR-005-2024',
                'experience' => 10,
                'fee' => 85000,
                'rating' => 4.8,
                'hospital' => 'RSIA Bunda Aliyah',
                'education' => 'Universitas Indonesia',
                'bio' => 'Dokter kandungan dan kebidanan dengan spesialisasi kehamilan risiko tinggi dan fertilitas.',
                'patients' => 2200,
                'reviews' => 310,
            ],
            [
                'name' => 'Dr. Hendra Gunawan',
                'email' => 'hendra.gunawan@koline.test',
                'specialization' => 'Dokter Umum',
                'str' => 'STR-006-2024',
                'experience' => 5,
                'fee' => 50000,
                'rating' => 4.6,
                'hospital' => 'Klinik Sehat Bersama',
                'education' => 'Universitas Diponegoro',
                'bio' => 'Dokter umum yang ramah dan sabar. Berpengalaman menangani ISPA, demam, dan konsultasi umum.',
                'patients' => 980,
                'reviews' => 145,
            ],
            [
                'name' => 'Dr. Arini Permata, M.Psi',
                'email' => 'arini.permata@koline.test',
                'specialization' => 'Psikiater',
                'str' => 'STR-007-2024',
                'experience' => 9,
                'fee' => 80000,
                'rating' => 4.9,
                'hospital' => 'RS Jiwa Prof. HB Saanin',
                'education' => 'Universitas Indonesia',
                'bio' => 'Psikiater berpengalaman menangani kecemasan, depresi, insomnia, dan gangguan bipolar.',
                'patients' => 1200,
                'reviews' => 195,
            ],
            [
                'name' => 'Dr. Fikri Hakim, Sp.THT',
                'email' => 'fikri.hakim@koline.test',
                'specialization' => 'Spesialis THT',
                'str' => 'STR-008-2024',
                'experience' => 11,
                'fee' => 72000,
                'rating' => 4.7,
                'hospital' => 'RSUP Dr. Sardjito',
                'education' => 'Universitas Gadjah Mada',
                'bio' => 'Spesialis THT dengan pengalaman luas dalam penanganan sinusitis, tonsil, dan gangguan pendengaran.',
                'patients' => 1750,
                'reviews' => 250,
            ],
        ];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        foreach ($doctors as $d) {
            $spec = Specialization::where('name', $d['specialization'])->first();
            if (!$spec) continue;

            $user = User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'doctor',
                    'gender' => 'male',
                    'email_verified_at' => now(),
                ]
            );

            $doctor = Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization_id' => $spec->id,
                    'str_number' => $d['str'],
                    'experience_years' => $d['experience'],
                    'consultation_fee' => $d['fee'],
                    'bio' => $d['bio'],
                    'hospital' => $d['hospital'],
                    'education' => $d['education'],
                    'rating' => $d['rating'],
                    'total_reviews' => $d['reviews'],
                    'total_patients' => $d['patients'],
                    'is_available' => true,
                    'is_verified' => true,
                ]
            );

            // Create schedules
            foreach ($days as $day) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00',
                    'end_time' => '17:00',
                    'max_patients' => 20,
                    'is_active' => true,
                ]);
            }
        }
    }
}
