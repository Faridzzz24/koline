@extends('layouts.app')
@section('title', 'Tambah Dokter Baru | KoLine')

@section('content')
<div style="max-width: 860px; margin: 0 auto;">

    <div class="main-header mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.75rem;">
                    ← Kembali ke Daftar Dokter
                </a>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Tambah Dokter Spesialis Baru</h1>
            <div style="font-size: 0.9rem; color: #94A3B8;">Daftarkan akun dokter baru beserta nomor STR, spesialisasi, dan tarif telekonsultasi</div>
        </div>
    </div>

    {{-- Validation Error Summary Banner --}}
    @if ($errors->any())
        <div class="card mb-6" style="padding: 1.25rem 1.5rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--r-lg);">
            <div style="font-weight: 700; color: #FCA5A5; margin-bottom: 0.5rem; font-size: 0.95rem;">
                ⚠️ Terjadi kesalahan saat menyimpan data dokter:
            </div>
            <ul style="color: #FCA5A5; font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 2rem 2.25rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
        <form action="{{ route('admin.dokter.store') }}" method="POST">
            @csrf

            <div class="grid grid-2 mb-6" style="gap: 1.5rem;">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nama Lengkap Dokter <span style="color: var(--clr-danger);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Contoh: dr. John Doe, Sp.PD" required>
                    @error('name')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Alamat Email <span style="color: var(--clr-danger);">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="dokter@koline.test" required>
                    @error('email')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Spesialisasi Medis <span style="color: var(--clr-danger);">*</span></label>
                    <select name="specialization_id" class="form-select" required>
                        <option value="">Pilih Spesialisasi Dokter...</option>
                        @foreach($specializations as $s)
                            <option value="{{ $s->id }}" {{ old('specialization_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialization_id')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nomor STR (Surat Tanda Registrasi) <span style="color: var(--clr-danger);">*</span></label>
                    <input type="text" name="str_number" value="{{ old('str_number') }}" class="form-input" placeholder="STR.12345.678" required>
                    @error('str_number')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Pengalaman Kerja (Tahun) <span style="color: var(--clr-danger);">*</span></label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', 5) }}" class="form-input" min="0" required>
                    @error('experience_years')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Tarif Telekonsultasi (Rp) <span style="color: var(--clr-danger);">*</span></label>
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', 75000) }}" class="form-input" step="1000" min="0" required>
                    @error('consultation_fee')<div style="color: var(--clr-danger); font-size: 0.775rem; margin-top: 0.375rem;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group mb-6">
                <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Rumah Sakit / Klinik Praktik Utam</label>
                <input type="text" name="hospital" value="{{ old('hospital') }}" class="form-input" placeholder="Contoh: RS Pusat Pertamina / Klinik Partner KoLine">
            </div>

            <div class="form-group mb-8">
                <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Biografi & Rekam Jejak Medis Singkat</label>
                <textarea name="bio" class="form-input" rows="4" placeholder="Tuliskan riwayat pendidikan atau pengalaman klinis dokter...">{{ old('bio') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary btn-lg" style="font-weight: 700; padding: 0.85rem 2rem;">
                    Simpan & Aktifkan Dokter
                </button>
                <a href="{{ route('admin.dokter.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection
