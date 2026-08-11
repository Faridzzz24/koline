<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        if (!$doctor) return redirect()->route('home')->with('error', 'Profil dokter tidak ditemukan.');

        $stats = [
            'total_patients' => $doctor->total_patients,
            'pending' => Consultation::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'active' => Consultation::where('doctor_id', $doctor->id)->whereIn('status', ['confirmed', 'active'])->count(),
            'completed' => Consultation::where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
        ];

        $pendingConsultations = Consultation::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->orderBy('consultation_date')
            ->take(5)->get();

        $activeConsultations = Consultation::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->orderBy('consultation_date')
            ->take(5)->get();

        return view('doctor.dashboard', compact('doctor', 'stats', 'pendingConsultations', 'activeConsultations'));
    }
}
