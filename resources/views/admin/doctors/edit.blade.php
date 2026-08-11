@extends('layouts.app')
@section('title', 'Edit Dokter Spesialis | KoLine')

@section('content')
<div style="max-width: 860px; margin: 0 auto;">

    <div class="main-header mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.75rem;">
                    ← Kembali ke Daftar Dokter
                </a>
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Edit Data Dokter Spesialis</h1>
            <div style="font-size: 0.9rem; color: #94A3B8;">Perbarui informasi profil, nomor STR, spesialisasi, dan tarif telekonsultasi</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card mb-6" style="padding: 1.25rem 1.5rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: var(--r-lg);">
            <div style="font-weight: 700; color: #FCA5A5; margin-bottom: 0.5rem; font-size: 0.95rem;">
                ⚠️ Terjadi kesalahan saat memperbarui data dokter:
            </div>
            <ul style="color: #FCA5A5; font-size: 0.875rem; padding-left: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 2rem 2.25rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
        <form action="{{ route('admin.dokter.update', $dokter) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="grid grid-2 mb-6" style="gap: 1.5rem;">
                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nama Lengkap Dokter</label>
                    <input type="text" value="{{ $dokter->user ? $dokter->user->name : '' }}" class="form-input" disabled style="opacity: 0.7;">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Alamat Email</label>
                    <input type="email" value="{{ $dokter->user ? $dokter->user->email : '' }}" class="form-input" disabled style="opacity: 0.7;">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Spesialisasi Medis</label>
                    <select name="specialization_id" class="form-select">
                        @foreach($specializations as $s)
                            <option value="{{ $s->id }}" {{ old('specialization_id', $dokter->specialization_id) == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Nomor STR</label>
                    <input type="text" name="str_number" value="{{ old('str_number', $dokter->str_number) }}" class="form-input" required>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Tarif Telekonsultasi (Rp)</label>
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', (int)$dokter->consultation_fee) }}" class="form-input" step="1000" min="0" required>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight: 700; color: var(--txt-heading);">Verifikasi STR</label>
                    <select name="is_verified" class="form-select">
                        <option value="1" {{ $dokter->is_verified ? 'selected' : '' }}>✓ Terverifikasi</option>
                        <option value="0" {{ !$dokter->is_verified ? 'selected' : '' }}>Belum Verifikasi</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn btn-primary btn-lg" style="font-weight: 700; padding: 0.85rem 2rem;">
                    💾 Perbarui Data Dokter
                </button>
                <a href="{{ route('admin.dokter.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection
