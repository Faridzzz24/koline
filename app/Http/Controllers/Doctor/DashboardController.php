<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctor = $user->doctor ?: Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            $specialization = \App\Models\Specialization::firstOrCreate(
                ['name' => 'Spesialis Penyakit Dalam'],
                ['slug' => 'spesialis-penyakit-dalam', 'description' => 'Spesialis Penyakit Dalam', 'icon' => 'stethoscope', 'is_active' => true]
            );
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $specialization->id,
                'str_number' => 'STR-' . $user->id . '-' . time(),
                'experience_years' => 5,
                'consultation_fee' => 75000,
                'bio' => 'Dokter spesialis berpengalaman di KoLine.',
                'hospital' => 'RS Partner KoLine',
                'education' => 'Universitas Indonesia',
                'is_available' => true,
                'is_verified' => true,
            ]);
        }

        $stats = [
            'total_patients' => Consultation::where('doctor_id', $doctor->id)->count(),
            'pending' => Consultation::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'active' => Consultation::where('doctor_id', $doctor->id)->whereIn('status', ['confirmed', 'active'])->count(),
            'completed' => Consultation::where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
        ];

        $pendingConsultations = Consultation::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        $pendingList = $pendingConsultations->map(function ($c) {
            $patientName = $c->patient ? $c->patient->name : 'Pasien';
            $dateStr = $c->consultation_date ? $c->consultation_date->format('d M Y') : date('d M Y');
            return [
                'id' => $c->id,
                'patient_name' => $patientName,
                'patient_initial' => strtoupper(substr($patientName, 0, 1)),
                'date' => $dateStr,
                'time' => substr($c->consultation_time ?? '00:00', 0, 5) . ' WIB',
                'complaint' => \Illuminate\Support\Str::limit($c->complaint ?? '', 80),
                'confirm_url' => route('consultations.confirm', $c),
                'show_url' => route('consultations.show', $c),
            ];
        });

        $activeConsultations = Consultation::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->orderByDesc('created_at')
            ->get();

        $activeList = $activeConsultations->map(function ($c) {
            $patientName = $c->patient ? $c->patient->name : 'Pasien';
            $dateStr = $c->consultation_date ? $c->consultation_date->format('d M Y') : date('d M Y');
            return [
                'id' => $c->id,
                'patient_name' => $patientName,
                'patient_initial' => strtoupper(substr($patientName, 0, 1)),
                'date' => $dateStr,
                'time' => substr($c->consultation_time ?? '00:00', 0, 5) . ' WIB',
                'show_url' => route('consultations.show', $c),
            ];
        });

        return view('doctor.dashboard', compact('doctor', 'stats', 'pendingConsultations', 'activeConsultations', 'pendingList', 'activeList'));
    }

    public function poll()
    {
        $user = Auth::user();
        if (!$user || !$user->isDoctor()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $doctor = $user->doctor ?: Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json([
                'pending_count' => 0,
                'active_count' => 0,
                'completed_count' => 0,
                'pending_consultations' => [],
                'active_consultations' => []
            ]);
        }

        $pendingConsultations = Consultation::with('patient:id,name')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get(['id', 'patient_id', 'consultation_date', 'consultation_time', 'complaint', 'status', 'created_at'])
            ->map(function ($c) {
                $patientName = $c->patient ? $c->patient->name : 'Pasien';
                $dateStr = $c->consultation_date ? $c->consultation_date->format('d M Y') : date('d M Y');
                return [
                    'id' => $c->id,
                    'patient_name' => $patientName,
                    'patient_initial' => strtoupper(substr($patientName, 0, 1)),
                    'date' => $dateStr,
                    'time' => substr($c->consultation_time ?? '00:00', 0, 5) . ' WIB',
                    'complaint' => \Illuminate\Support\Str::limit($c->complaint ?? '', 80),
                    'confirm_url' => route('consultations.confirm', $c),
                    'show_url' => route('consultations.show', $c),
                ];
            });

        $activeConsultations = Consultation::with('patient:id,name')
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->orderByDesc('created_at')
            ->get(['id', 'patient_id', 'consultation_date', 'consultation_time', 'status', 'created_at'])
            ->map(function ($c) {
                $patientName = $c->patient ? $c->patient->name : 'Pasien';
                $dateStr = $c->consultation_date ? $c->consultation_date->format('d M Y') : date('d M Y');
                return [
                    'id' => $c->id,
                    'patient_name' => $patientName,
                    'patient_initial' => strtoupper(substr($patientName, 0, 1)),
                    'date' => $dateStr,
                    'time' => substr($c->consultation_time ?? '00:00', 0, 5) . ' WIB',
                    'show_url' => route('consultations.show', $c),
                ];
            });

        $completedCount = Consultation::where('doctor_id', $doctor->id)->where('status', 'completed')->count();

        return response()->json([
            'pending_count' => $pendingConsultations->count(),
            'active_count' => $activeConsultations->count(),
            'completed_count' => $completedCount,
            'pending_consultations' => $pendingConsultations,
            'active_consultations' => $activeConsultations,
        ]);
    }
}
