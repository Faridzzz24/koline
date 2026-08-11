<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $doctorUser = User::where('email', 'andi.wijaya@koline.test')->first();
        $doctor = $doctorUser ? Doctor::where('user_id', $doctorUser->id)->first() : Doctor::first();
        $patient = User::where('email', 'pasien@koline.test')->first();

        if (!$doctor || !$patient) {
            return;
        }

        // Create sample consultation ID 1
        $consultation = Consultation::firstOrCreate(
            ['id' => 1],
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'consultation_date' => now()->toDateString(),
                'consultation_time' => '14:00:00',
                'duration_hours' => 1,
                'fee' => $doctor->consultation_fee ?? 75000,
                'status' => 'pending',
                'complaint' => 'Saya merasa pusing, demam ringan sejak kemarin, dan tenggorokan agak sakit.',
                'payment_status' => 'paid',
                'payment_method' => 'qris',
            ]
        );

        // Add initial message if none exist
        if ($consultation->messages()->count() === 0) {
            ConsultationMessage::create([
                'consultation_id' => $consultation->id,
                'sender_id' => $patient->id,
                'message' => 'Halo Dr. Andi, saya ingin berkonsultasi mengenai keluhan pusing dan demam yang saya alami.',
            ]);
        }
    }
}
