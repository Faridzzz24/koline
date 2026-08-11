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
            $specializationId = \App\Models\Specialization::first()?->id ?? 1;
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $specializationId,
                'str_number' => 'STR-' . $user->id . '-2024',
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

        $activeConsultations = Consultation::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->orderByDesc('created_at')
            ->get();

        return view('doctor.dashboard', compact('doctor', 'stats', 'pendingConsultations', 'activeConsultations'));
    }

    public function poll()
    {
        $user = Auth::user();
        if (!$user->isDoctor()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $doctor = $user->doctor ?: Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['pending_count' => 0, 'active_count' => 0, 'completed_count' => 0, 'pending_consultations' => []]);
        }

        $pendingConsultations = Consultation::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'patient_name' => $c->patient->name,
                    'patient_initial' => strtoupper(substr($c->patient->name, 0, 1)),
                    'date' => $c->consultation_date->format('d M Y'),
                    'time' => substr($c->consultation_time, 0, 5) . ' WIB',
                    'complaint' => \Illuminate\Support\Str::limit($c->complaint, 80),
                    'confirm_url' => route('consultations.confirm', $c),
                    'show_url' => route('consultations.show', $c),
                ];
            });

        $activeCount = Consultation::where('doctor_id', $doctor->id)->whereIn('status', ['confirmed', 'active'])->count();
        $completedCount = Consultation::where('doctor_id', $doctor->id)->where('status', 'completed')->count();

        return response()->json([
            'pending_count' => $pendingConsultations->count(),
            'active_count' => $activeCount,
            'completed_count' => $completedCount,
            'pending_consultations' => $pendingConsultations,
        ]);
    }
}
