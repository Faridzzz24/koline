@extends('layouts.guest')
@section('title', 'Cari Dokter Spesialis')

@section('content')
<div class="page-wrapper">
    <div class="container">

        {{-- Page Header --}}
        <div style="margin-bottom: 3rem;">
            <h1 style="font-size: clamp(2.25rem, 3.75vw, 3rem); font-weight: 800; margin-bottom: 0.75rem; line-height: 1.25;">
                Cari Dokter <span class="text-gradient">Spesialis Medis</span>
            </h1>
            <p style="color: var(--txt-muted); font-size: 1.1rem; max-width: 680px; line-height: 1.7;">
                {{ $doctors->total() }} dokter spesialis terverifikasi Kementerian Kesehatan dengan STR & SIP resmi, siap melayani telekonsultasi Anda.
            </p>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('doctors.index') }}" class="filter-bar">
            <div class="doctor-filter-grid">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">🔍 Cari Dokter / RS</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama dokter atau rumah sakit..." class="form-input">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">🩺 Spesialisasi</label>
                    <select name="specialization" class="form-select">
                        <option value="">Semua Spesialisasi</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec->id }}" {{ request('specialization') == $spec->id ? 'selected' : '' }}>
                                {{ $spec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Status Sesi</label>
                    <select name="available" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('available') ? 'selected' : '' }}>Online Sekarang</option>
                    </select>
                </div>
                <div class="doctor-filter-actions">
                    <button type="submit" class="btn btn-primary" style="height: 48px; width: 100%;">Cari Dokter</button>
                    <a href="{{ route('doctors.index') }}" class="btn btn-ghost" style="height: 48px; width: 100%; text-align: center; display: flex; align-items: center; justify-content: center;">Reset</a>
                </div>
            </div>
        </form>

        {{-- Doctors Grid --}}

        @if($doctors->isEmpty())
            <div class="card" style="text-align: center; padding: 5rem 2rem;">
                <h3 style="color: var(--txt-heading); margin-bottom: 0.75rem;">Dokter Tidak Ditemukan</h3>
                <p style="color: var(--txt-muted); max-width: 480px; margin: 0 auto 1.5rem;">Coba ubah kata kunci pencarian atau sesuaikan opsi filter spesialisasi.</p>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline" style="display: inline-flex; width: auto;">Tampilkan Semua Dokter</a>
            </div>
        @else
            <div class="grid-auto-fit">
                @foreach($doctors as $doctor)
                    @php
                        $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $doctor->user->name);
                        $words = explode(' ', trim($cleanName));
                        $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <div class="doctor-card">
                        <div>
                            {{-- Doctor Avatar & Name Header --}}
                            <div class="flex items-center gap-4 mb-4">
                                <div class="initial-avatar">
                                    {{ $initials }}
                                </div>
                                <div style="min-width: 0; flex: 1;">
                                    <div class="doctor-name" title="{{ $doctor->user->name }}">
                                        {{ $doctor->user->name }}
                                    </div>
                                    <div class="doctor-spec">{{ $doctor->specialization->name }}</div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span style="color: #F59E0B; font-weight: 700; font-size: 0.85rem;">★ {{ $doctor->rating }}</span>
                                        <span style="font-size: 0.775rem; color: var(--txt-muted);">({{ $doctor->total_reviews }} ulasan)</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Hospital & Experience --}}
                            <div style="font-size: 0.875rem; color: var(--txt-body); display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.5rem; padding-top: 0.5rem;">
                                <div class="flex items-center gap-2" style="color: var(--txt-muted);">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                                    <span>{{ Str::limit($doctor->hospital ?? 'Klinik Medis Utama', 26) }}</span>
                                </div>
                                <div class="flex items-center gap-2" style="color: var(--txt-muted);">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $doctor->experience_years }} Tahun Pengalaman</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Action --}}
                        <div style="border-top: 1px solid var(--bdr-subtle); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.15rem;">{{ $doctor->formatted_fee }}</div>
                                <div style="font-size: 0.75rem; color: var(--txt-muted);">per sesi (30 Mnt)</div>
                            </div>
                            <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-primary btn-sm">Konsultasi</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($doctors->hasPages())
                <div style="margin-top: 3.5rem; display: flex; justify-content: center;">
                    {{ $doctors->withQueryString()->links('vendor.pagination.custom') }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
