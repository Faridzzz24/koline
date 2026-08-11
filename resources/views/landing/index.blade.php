@extends('layouts.guest')

@section('title', 'Platform Layanan Kesehatan Daring Terpadu')
@section('meta_description', 'KoLine (Konsultasi Online) - Layanan konsultasi dokter spesialis terpercaya, cek kesehatan mandiri, dan apotek digital terintegrasi 24/7.')

@section('content')

{{-- ── 1. HERO SECTION ──────────────────────────────── --}}
<section class="hero-section" style="padding: 7.5rem 0 5rem; background: radial-gradient(circle at 50% 20%, rgba(2, 132, 199, 0.16) 0%, transparent 65%), var(--bg-dark); position: relative; overflow: hidden; border-bottom: 1px solid var(--bdr-subtle);">
    <div class="container">

        {{-- Desktop: 2-column, Mobile: 1-column --}}
        <div class="hero-inner-grid">

            {{-- LEFT: Hero Copy --}}
            <div style="min-width: 0;">

                {{-- Badge: shorter on mobile --}}
                <div class="hero-badge">
                    <span style="width: 7px; height: 7px; background: var(--clr-teal-light); border-radius: 50%; display: inline-block; flex-shrink: 0;"></span>
                    <span class="hero-badge-text">2.500+ Dokter Spesialis Terverifikasi STR &amp; SIP Resmi</span>
                    <span class="hero-badge-short">2.500+ Dokter Terverifikasi</span>
                </div>

                <h1 class="hero-heading">
                    Layanan Kesehatan Daring<br>
                    <span class="text-gradient">Terpadu &amp; Terpercaya</span><br>
                    Untuk Keluarga Anda
                </h1>

                <p class="hero-desc">
                    Akses langsung ke tim medis profesional, analisis indikator kesehatan mandiri, serta pengadaan produk farmasi resmi dengan standar tinggi.
                </p>

                <div class="hero-cta-buttons">
                    <a href="{{ route('doctors.index') }}" class="btn btn-primary hero-btn-main">
                        Konsultasi Dokter Sekarang
                    </a>
                    <a href="{{ route('health-check.index') }}" class="btn btn-outline hero-btn-sec">
                        Cek Kesehatan Mandiri
                    </a>
                </div>

                {{-- Stats Row: 4 desktop / 3 mobile --}}
                <div class="hero-stats-row">
                    <div class="stat-item">
                        <div class="stat-number" data-count="2500">0</div>
                        <div class="stat-label">Dokter Medis</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="150000">0</div>
                        <div class="stat-label">Pasien Aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" data-count="98">0</div>
                        <div class="stat-label">% Kepuasan</div>
                    </div>
                    {{-- 4th stat: hidden on mobile --}}
                    <div class="stat-item stat-hide-mobile">
                        <div class="stat-number" style="color: var(--clr-teal-light);">24/7</div>
                        <div class="stat-label">Siaga Medis</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Preview Card (Desktop only) --}}
            <div class="hero-preview-card">
                <div class="card" style="box-shadow: var(--shadow-lg), var(--shadow-glow); background: var(--bg-card); border-color: rgba(2, 132, 199, 0.25); padding: 2rem; gap: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <span class="badge badge-success" style="padding: 0.4rem 0.85rem;">● Siaga Sesi Online</span>
                        <span style="font-size: 0.8rem; color: var(--txt-muted); font-weight: 500;">Respon &lt; 5 Menit</span>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="initial-avatar" style="width: 54px; height: 54px; min-width: 54px;">AW</div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 700; color: var(--txt-heading); font-size: 1rem; line-height: 1.4;">dr. Andi Wijaya, Sp.PD</div>
                            <div style="font-size: 0.825rem; color: var(--clr-teal-light); font-weight: 600; margin-top: 0.1rem;">Spesialis Penyakit Dalam</div>
                            <div style="font-size: 0.75rem; color: var(--txt-muted); margin-top: 0.2rem;">RS Harapan Utama · 8 Thn Pengalaman</div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem;">
                        <div class="flex-between" style="font-size: 0.875rem;">
                            <span style="color: var(--txt-muted);">Biaya Sesi Konsultasi</span>
                            <span style="font-weight: 800; color: var(--txt-heading); font-size: 1.05rem;">Rp 75.000</span>
                        </div>
                        <div class="flex-between" style="font-size: 0.875rem;">
                            <span style="color: var(--txt-muted);">Tingkat Ulasan Medis</span>
                            <span style="font-weight: 700; color: #F59E0B;">★ 4.9 <small style="color: var(--txt-muted);">(340 ulasan)</small></span>
                        </div>
                    </div>
                    <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-block btn-lg">Mulai Konsultasi Daring →</a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── 2. FITUR UNGGULAN ───────────────────────────── --}}
<section class="section section-surface">
    <div class="container">
        <div class="section-center-header">
            <div class="badge badge-primary mb-2" style="display: inline-flex;">Layanan Terintegrasi</div>
            <h2 style="margin-bottom: 0.5rem; font-size: clamp(1.375rem, 3vw, 2.25rem);">Platform Medis Terlengkap di Indonesia</h2>
            <p style="color: var(--txt-muted); font-size: 0.95rem; line-height: 1.6; margin: 0;">Tiga pilar utama layanan KoLine untuk memastikan kesehatan Anda terjaga secara komprehensif.</p>
        </div>
        <div class="grid grid-3">
            <a href="{{ route('doctors.index') }}" class="card feature-card" style="text-decoration: none;">
                <div class="feature-icon" style="background: rgba(2,132,199,0.15); color: var(--clr-brand-light);">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3>Dokter Spesialis Daring</h3>
                <p style="font-size: 0.9rem; color: var(--txt-muted); line-height: 1.7; margin: 0;">Layanan telekonsultasi cepat dengan rekomendasi medis komprehensif dan resep resmi.</p>
            </a>
            <a href="{{ route('health-check.bmi') }}" class="card feature-card" style="text-decoration: none;">
                <div class="feature-icon" style="background: rgba(13,148,136,0.15); color: var(--clr-teal-light);">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3>Pemeriksaan Mandiri</h3>
                <p style="font-size: 0.9rem; color: var(--txt-muted); line-height: 1.7; margin: 0;">Alat pengukur indikator kesehatan BMI dan analisis rujukan medis awal.</p>
            </a>
            <a href="{{ route('medicines.index') }}" class="card feature-card" style="text-decoration: none;">
                <div class="feature-icon" style="background: rgba(16,185,129,0.15); color: #34D399;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3>Apotek &amp; Suplemen</h3>
                <p style="font-size: 0.9rem; color: var(--txt-muted); line-height: 1.7; margin: 0;">Pengadaan obat resmi, suplemen kesehatan, dan alat medis terverifikasi BPOM.</p>
            </a>
        </div>
    </div>
</section>

{{-- ── 3. DOKTER MEDIS TERPOPULER ────────────────────── --}}
<section class="section" style="background: var(--bg-dark);">
    <div class="container">

        {{-- Section Header --}}
        <div class="doctors-section-header">
            <div>
                <div class="badge badge-teal mb-2" style="display: inline-flex;">Pilihan Spesialisasi</div>
                <h2 style="margin-bottom: 0; font-size: clamp(1.375rem, 3vw, 2.25rem);">Dokter Medis Terpopuler</h2>
            </div>
            {{-- "Lihat Semua" link: hidden on mobile (there's a button below on mobile) --}}
            <a href="{{ route('doctors.index') }}" class="btn btn-outline doctors-see-all-btn">Lihat Semua Dokter →</a>
        </div>

        <div class="grid grid-4 landing-doctors-grid">
            @foreach($doctors as $doctor)
                @php
                    $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $doctor->user->name);
                    $words = explode(' ', trim($cleanName));
                    $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div class="doctor-card landing-doctor-item">
                    <div>
                        <div class="flex items-start gap-3.5 mb-4">
                            <div class="initial-avatar" style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; font-size: 1rem;">{{ $initials }}</div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-size: 0.95rem; font-weight: 700; color: var(--txt-heading); line-height: 1.3; margin-bottom: 0.2rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $doctor->user->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--clr-teal-light); font-weight: 600; margin-bottom: 0.35rem;">{{ $doctor->specialization->name }}</div>
                                <div style="font-size: 0.775rem; color: var(--txt-muted); line-height: 1.4;">{{ Str::limit($doctor->hospital ?? 'RS Pusat Pertamina', 24) }} · {{ $doctor->experience_years }} Thn</div>
                            </div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid var(--bdr-subtle); padding-top: 1rem; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div>
                            <div style="font-weight: 800; color: var(--txt-heading); font-size: 1rem;">{{ $doctor->formatted_fee }}</div>
                            <div style="font-size: 0.7rem; color: var(--txt-muted);">per sesi</div>
                        </div>
                        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-primary btn-sm" style="flex-shrink: 0; white-space: nowrap;">Konsultasi</a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Mobile: Show "Lihat Semua" as full-width button at bottom --}}
        <div class="doctors-mobile-cta">
            <a href="{{ route('doctors.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 1.25rem;">Lihat Semua Dokter →</a>
        </div>

    </div>
</section>

@endsection

