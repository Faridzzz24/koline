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
}
