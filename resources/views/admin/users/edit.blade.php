@extends('layouts.app')
@section('title', 'Edit Pengguna | KoLine')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">

    <div class="main-header mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.75rem;">
                    ← Kembali ke Manajemen User
                </a>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Edit Data Pengguna</h1>
            <div style="font-size: 0.9rem; color: #94A3B8;">Perbarui informasi profil pengguna dan hak akses di platform KoLine</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card mb-6" style="padding: 1.25rem 1.5rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--r-lg);">
            <div style="font-weight: 700; color: #FCA5A5; margin-bottom: 0.5rem; font-size: 0.95rem;">
                ⚠️ Terjadi kesalahan saat memperbarui data pengguna:
            </div>
            <ul style="color: #FCA5A5; font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 2rem 2.25rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-2 mb-6" style="gap: 1.5rem;">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nama Lengkap Pengguna <span style="color: var(--clr-danger);">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    @error('name')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Alamat Email <span style="color: var(--clr-danger);">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    @error('email')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0" style="grid-column: span 2;">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Role / Akses Pengguna <span style="color: var(--clr-danger);">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="patient" {{ old('role', $user->role) === 'patient' ? 'selected' : '' }}>👤 Pasien (User Biasa)</option>
                        <option value="doctor" {{ old('role', $user->role) === 'doctor' ? 'selected' : '' }}>👨‍⚕️ Dokter Spesialis</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>🛡️ Administrator Platform</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary btn-lg" style="font-weight: 700; padding: 0.85rem 2rem;">
                    💾 Perbarui Akun Pengguna
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection
