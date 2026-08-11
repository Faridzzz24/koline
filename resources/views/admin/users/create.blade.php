@extends('layouts.app')
@section('title', 'Tambah Akun Baru | KoLine')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">

    <div class="main-header mb-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.75rem;">
                    ← Kembali ke Manajemen User
                </a>
            </div>
            <h1 style="font-size: 1.65rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Tambah Akun Pengguna Baru</h1>
            <div style="font-size: 0.875rem; color: var(--txt-muted);">Daftarkan akun Pasien, Dokter Spesialis, atau Administrator untuk platform KoLine</div>
        </div>
    </div>

    {{-- Validation Error Summary Banner --}}
    @if ($errors->any())
        <div class="card mb-5" style="padding: 1rem 1.25rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--r-lg);">
            <div style="font-weight: 700; color: #FCA5A5; margin-bottom: 0.35rem; font-size: 0.9rem;">
                ⚠️ Terjadi kesalahan saat membuat akun:
            </div>
            <ul style="color: #FCA5A5; font-size: 0.85rem; padding-left: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 1.75rem 2rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); border-radius: var(--r-xl);" x-data="{ role: '{{ old('role', 'patient') }}' }">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-2 mb-4" style="gap: 1.25rem;">
                {{-- Row 1, Col 1: Role Selection --}}
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Peran / Role Akun <span style="color: var(--clr-danger);">*</span></label>
                    <select name="role" x-model="role" class="form-select" required>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Pasien</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Dokter Spesialis</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>

                {{-- Row 1, Col 2: Nama Lengkap --}}
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nama Lengkap <span style="color: var(--clr-danger);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Contoh: Budi Santoso" required>
                    @error('name')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid grid-2 mb-4" style="gap: 1.25rem;">
                {{-- Row 2, Col 1: Alamat Email --}}
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Alamat Email <span style="color: var(--clr-danger);">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="pengguna@koline.test" required>
                    @error('email')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>

                {{-- Row 2, Col 2: Password --}}
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Password Akun <span style="color: var(--clr-danger);">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password (minimal 6 karakter)" required>
                    @error('password')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Extra Fields for Doctor Role --}}
            <div x-show="role === 'doctor'" x-cloak style="padding-top: 1.25rem; margin-top: 1.25rem; border-top: 1px solid var(--bdr-subtle);">
                <div style="font-weight: 700; color: var(--clr-brand-light); font-size: 0.95rem; margin-bottom: 1rem;">
                    Informasi Tambahan Dokter Spesialis
                </div>

                <div class="grid grid-2 mb-4" style="gap: 1.25rem;">
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Spesialisasi Medis <span style="color: var(--clr-danger);">*</span></label>
                        <select name="specialization_id" class="form-select" :required="role === 'doctor'">
                            <option value="">Pilih Spesialisasi Dokter...</option>
                            @foreach($specializations as $s)
                                <option value="{{ $s->id }}" {{ old('specialization_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization_id')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nomor STR <span style="color: var(--clr-danger);">*</span></label>
                        <input type="text" name="str_number" value="{{ old('str_number') }}" class="form-input" placeholder="STR-009-2026" :required="role === 'doctor'">
                        @error('str_number')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="grid grid-2 mb-4" style="gap: 1.25rem;">
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Pengalaman Kerja (Tahun)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', 5) }}" class="form-input" min="0">
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Tarif Telekonsultasi (Rp) <span style="color: var(--clr-danger);">*</span></label>
                        <input type="number" name="consultation_fee" value="{{ old('consultation_fee', 75000) }}" class="form-input" step="1000" min="0" :required="role === 'doctor'">
                        @error('consultation_fee')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.25rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Rumah Sakit / Klinik Praktik</label>
                    <input type="text" name="hospital" value="{{ old('hospital') }}" class="form-input" placeholder="Contoh: RS Pusat Pertamina">
                </div>
            </div>

            <div class="flex items-center gap-3" style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--bdr-subtle);">
                <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 0.75rem 1.75rem;">
                    Simpan & Aktifkan Akun
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection
