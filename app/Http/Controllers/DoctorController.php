<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->isDoctor()) {
                return redirect()->route('doctor.dashboard');
            }
        }

        $query = Doctor::with(['user', 'specialization'])
            ->has('user')
            ->where('is_verified', true);

        if ($request->filled('specialization')) {
            $query->where('specialization_id', $request->specialization);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('available')) {
            $query->where('is_available', true);
        }

        $doctors = $query->orderByDesc('rating')->paginate(12);
        $specializations = Specialization::where('is_active', true)->get();

        return view('doctors.index', compact('doctors', 'specializations'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialization', 'schedules' => fn($q) => $q->where('is_active', true)->orderBy('day_of_week')]);
        $reviews = Consultation::where('doctor_id', $doctor->id)
            ->whereNotNull('rating')
            ->with('patient')
            ->latest()
            ->take(5)
            ->get();

        return view('doctors.show', compact('doctor', 'reviews'));
    }

    public function book(Request $request, Doctor $doctor)
    {
        $request->validate([
            'consultation_date' => 'required|date|after_or_equal:today',
            'consultation_time' => 'required',
            'duration_hours' => 'nullable|integer|min:1|max:3',
            'complaint' => 'required|min:10|max:1000',
        ]);

        $duration = (int) ($request->duration_hours ?? 1);
        if (!in_array($duration, [1, 2, 3])) {
            $duration = 1;
        }

        $calculatedFee = $doctor->consultation_fee * $duration;

        $consultation = Consultation::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $doctor->id,
            'consultation_date' => $request->consultation_date,
            'consultation_time' => $request->consultation_time,
            'duration_hours' => $duration,
            'complaint' => $request->complaint,
            'fee' => $calculatedFee,
            'status' => 'pending',
        ]);

        $doctor->increment('total_patients');

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Konsultasi berhasil dibooking! Silakan tunggu konfirmasi dari dokter.');
    }
}
