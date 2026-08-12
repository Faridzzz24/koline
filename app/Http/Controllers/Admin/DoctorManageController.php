<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorManageController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialization'])->latest('id')->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function show(Doctor $dokter)
    {
        $dokter->load(['user', 'specialization', 'schedules']);
        return view('admin.doctors.show', compact('dokter'));
    }

    public function update(Request $request, Doctor $dokter)
    {
        $request->validate([
            'specialization_id' => 'nullable|exists:specializations,id',
            'str_number' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);
        $data = array_filter($request->only(['specialization_id', 'str_number', 'is_verified', 'is_available', 'consultation_fee']), fn($v) => !is_null($v));
        $dokter->update($data);
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter ' . ($dokter->user ? $dokter->user->name : '') . ' berhasil diperbarui.');
    }

    public function destroy(Doctor $dokter)
    {
        $name = $dokter->user ? $dokter->user->name : 'Dokter';
        $userEmail = $dokter->user ? $dokter->user->email : null;
        
        // Purge schedules
        $dokter->schedules()->delete();
        
        // Permanently delete user and doctor records
        if ($dokter->user) {
            $dokter->user()->delete();
        }
        $dokter->delete();

        if ($userEmail) {
            \App\Services\UserRegistry::removeUser($userEmail);
        }

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter ' . $name . ' telah dihapus secara permanen dari sistem KoLine.');
    }

    public function create() 
    { 
        return view('admin.doctors.create', ['specializations' => Specialization::all()]); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 
            'email' => 'required|email|unique:users,email',
            'specialization_id' => 'required|exists:specializations,id',
            'str_number' => 'required|string|unique:doctors,str_number',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'hospital' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ], [
            'email.unique' => 'Alamat email ini sudah terdaftar di sistem.',
            'str_number.unique' => 'Nomor STR ini sudah terdaftar di sistem.',
            'specialization_id.required' => 'Spesialisasi dokter wajib dipilih.',
        ]);

        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->password ?? 'password123'),
            'role' => 'doctor', 
            'email_verified_at' => now(),
        ]);

        $doctorData = [
            'specialization_id' => $request->specialization_id,
            'str_number' => $request->str_number,
            'experience_years' => $request->experience_years,
            'consultation_fee' => $request->consultation_fee,
            'bio' => $request->bio,
            'hospital' => $request->hospital ?? 'RS Partner KoLine',
        ];

        Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $request->specialization_id,
            'str_number' => $request->str_number,
            'experience_years' => $request->experience_years,
            'consultation_fee' => $request->consultation_fee,
            'bio' => $request->bio,
            'hospital' => $request->hospital ?? 'RS Partner KoLine',
            'is_verified' => true, 
            'is_available' => true,
        ]);

        \App\Services\UserRegistry::registerUser($user, $doctorData);

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter ' . $user->name . ' berhasil ditambahkan dan langsung aktif di platform KoLine!');
    }

    public function edit(Doctor $dokter)
    {
        return view('admin.doctors.edit', ['dokter' => $dokter, 'specializations' => Specialization::all()]);
    }
}
