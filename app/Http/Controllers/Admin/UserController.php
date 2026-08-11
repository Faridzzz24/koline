<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $specializations = \App\Models\Specialization::all();
        return view('admin.users.edit', compact('user', 'specializations'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:patient,doctor,admin',
        ]);

        $user->update($request->only(['name', 'email', 'role']));
        return redirect()->route('admin.users.index')->with('success', 'Akun ' . $user->name . ' berhasil diperbarui.');
    }

    public function create()
    {
        $specializations = \App\Models\Specialization::all();
        return view('admin.users.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:patient,doctor,admin',
        ];

        if ($request->role === 'doctor') {
            $rules['specialization_id'] = 'required|exists:specializations,id';
            $rules['str_number'] = 'required|string|unique:doctors,str_number';
            $rules['consultation_fee'] = 'required|numeric|min:0';
            $rules['experience_years'] = 'nullable|integer|min:0';
            $rules['hospital'] = 'nullable|string|max:255';
        }

        $request->validate($rules, [
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
            'str_number.unique' => 'Nomor STR ini sudah terdaftar.',
            'specialization_id.required' => 'Spesialisasi dokter wajib dipilih.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        if ($request->role === 'doctor') {
            \App\Models\Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $request->specialization_id,
                'str_number' => $request->str_number,
                'consultation_fee' => $request->consultation_fee,
                'experience_years' => $request->experience_years ?? 0,
                'hospital' => $request->hospital ?? 'RS Partner KoLine',
                'is_verified' => true,
                'is_available' => true,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun ' . ucfirst($request->role) . ' (' . $user->name . ') berhasil dibuat dan aktif.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Tidak bisa hapus akun sendiri.');
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
