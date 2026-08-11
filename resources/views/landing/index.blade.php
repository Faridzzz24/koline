@extends('layouts.guest')

@section('title', 'Platform Layanan Kesehatan Daring Terpadu')
@section('meta_description', 'KoLine (Konsultasi Online) - Layanan konsultasi dokter spesialis terpercaya, cek kesehatan mandiri, dan apotek digital terintegrasi 24/7.')

@section('content')

{{-- ── 1. HERO SECTION ──────────────────────────────── --}}
<section class="hero-section" style="padding: 7.5rem 0 5rem; background: radial-gradient(circle at 50% 20%, rgba(2, 132, 199, 0.16) 0%, transparent 65%), var(--bg-dark); position: relative; overflow: hidden; border-bottom: 1px solid var(--bdr-subtle);">
    <div class="container">

        @push('styles')
        <style>
        .hero-inner-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 3rem;
            align-items: center;
        }
        @media (max-width: 991px) {
            .hero-inner-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
        </style>
        @endpush

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

            {{-- RIGHT: Interactive Featured Doctor Teleconsultation Card Widget --}}
            <div class="hero-right-widget" style="min-width: 0;">
                <div class="card" style="padding: 1.75rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle); border-radius: var(--r-xl); box-shadow: 0 20px 40px rgba(0,0,0,0.4); position: relative; overflow: hidden;">
                    
                    {{-- Top Badge Row --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                        <span style="font-size: 0.775rem; font-weight: 700; color: #34D399; background: rgba(52, 211, 153, 0.12); padding: 0.35rem 0.85rem; border-radius: var(--r-full); border: 1px solid rgba(52, 211, 153, 0.3); display: inline-flex; align-items: center; gap: 0.4rem;">
                            <span style="width: 7px; height: 7px; background: #34D399; border-radius: 50%; display: inline-block;"></span>
                            Siaga Sesi Online
                        </span>
                        <span style="font-size: 0.775rem; color: var(--txt-muted); font-weight: 600;">
                            Respon &lt; 5 Menit
                        </span>
                    </div>

                    {{-- Featured Doctor Info Card --}}
                    @php
                        $heroDoctor = $doctors->first();
                        $heroDocName = ($heroDoctor && $heroDoctor->user) ? $heroDoctor->user->name : 'dr. Andi Wijaya, Sp.PD';
                        $heroDocSpec = ($heroDoctor && $heroDoctor->specialization) ? $heroDoctor->specialization->name : 'Spesialis Penyakit Dalam';
                        $heroDocFee = $heroDoctor ? number_format($heroDoctor->consultation_fee, 0, ',', '.') : '75.000';
                        $heroDocHospital = $heroDoctor ? ($heroDoctor->hospital ?? 'RS Harapan Utama') : 'RS Harapan Utama';
                        $heroDocExperience = $heroDoctor ? ($heroDoctor->experience_years ?? 8) : 8;
                        $heroDocRating = $heroDoctor ? number_format($heroDoctor->rating, 1) : '4.9';
                        $heroDocReviews = $heroDoctor ? ($heroDoctor->total_reviews ?? 340) : 340;

                        $cleanDocName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $heroDocName);
                        $cleanDocName = preg_replace('/,?\s*Sp\.[A-Z]+.*$/i', '', $cleanDocName);
                        $words = explode(' ', trim($cleanDocName));
                        $heroInitials = strtoupper(substr($words[0] ?? 'A', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : 'W'));
                    @endphp

                    <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-surface); padding: 1.25rem; border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle); margin-bottom: 1.25rem;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, rgba(2, 132, 199, 0.25), rgba(13, 148, 136, 0.25)); border: 2px solid var(--clr-brand); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--clr-brand-light); font-size: 1.2rem; flex-shrink: 0; box-shadow: 0 0 15px rgba(2, 132, 199, 0.3);">
                            {{ $heroInitials }}
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.05rem; line-height: 1.25; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $heroDocName }}
                            </div>
                            <div style="font-size: 0.8rem; color: var(--clr-teal-light); font-weight: 600; margin-bottom: 0.15rem;">
                                {{ $heroDocSpec }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--txt-muted);">
                                {{ $heroDocHospital }} · {{ $heroDocExperience }} Thn Pengalaman
                            </div>
                        </div>
                    </div>

                    {{-- Fee & Rating Summary --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 0; border-top: 1px solid var(--bdr-subtle); border-bottom: 1px solid var(--bdr-subtle); margin-bottom: 1.25rem;">
                        <div>
                            <div style="font-size: 0.75rem; color: var(--txt-muted); margin-bottom: 0.1rem;">Biaya Sesi Konsultasi</div>
                            <div style="font-size: 1.15rem; font-weight: 800; color: var(--txt-heading);">Rp {{ $heroDocFee }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.75rem; color: var(--txt-muted); margin-bottom: 0.1rem;">Tingkat Ulasan Medis</div>
                            <div style="font-size: 0.925rem; font-weight: 700; color: #F59E0B; display: flex; align-items: center; gap: 0.25rem; justify-content: flex-end;">
                                ★ {{ $heroDocRating }} <span style="font-size: 0.75rem; color: var(--txt-muted); font-weight: 400;">({{ $heroDocReviews }} ulasan)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Direct CTA --}}
                    <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-block btn-lg" style="font-weight: 800; letter-spacing: 0.01em; box-shadow: 0 8px 25px rgba(2, 132, 199, 0.35);">
                        Mulai Konsultasi Daring →
                    </a>
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
                        {{-- Avatar & Doctor Name Header --}}
                        <div class="flex items-center gap-4 mb-3">
                            <div class="initial-avatar" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; font-size: 1.05rem; flex-shrink: 0;">{{ $initials }}</div>
                            <div style="min-width: 0; flex: 1;">
                                <div style="font-size: 0.975rem; font-weight: 700; color: var(--txt-heading); line-height: 1.3; margin-bottom: 0.2rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $doctor->user->name }}</div>
                                <div style="font-size: 0.825rem; color: var(--clr-teal-light); font-weight: 600;">{{ $doctor->specialization->name }}</div>
                            </div>
                        </div>

                        {{-- Hospital & Experience Info (Spaced lower with clear margin & icon) --}}
                        <div style="font-size: 0.8rem; color: var(--txt-muted); margin-top: 0.75rem; margin-bottom: 1.25rem; line-height: 1.5; display: flex; align-items: center; gap: 0.4rem;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; opacity:0.75;"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                            <span>{{ Str::limit($doctor->hospital ?? 'RS Pusat Pertamina', 28) }} · {{ $doctor->experience_years }} Thn</span>
                        </div>
                    </div>

                    {{-- Footer Fee & Consultation CTA --}}
                    <div style="border-top: 1px solid var(--bdr-subtle); padding-top: 1rem; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div>
                            <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.05rem;">{{ $doctor->formatted_fee }}</div>
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

