@extends('layouts.guest')

@section('title', 'Platform Layanan Kesehatan Daring Terpadu')
@section('meta_description', 'KoLine (Konsultasi Online) - Layanan konsultasi dokter spesialis terpercaya, cek kesehatan mandiri, dan apotek digital terintegrasi 24/7.')

@section('content')

{{-- ── 1. HERO SECTION ──────────────────────────────── --}}
<section style="padding: 9rem 0 6.5rem; background: radial-gradient(circle at 50% 20%, rgba(2, 132, 199, 0.16) 0%, transparent 65%), var(--bg-dark); position: relative; overflow: hidden; border-bottom: 1px solid var(--bdr-subtle);">
    <div class="container">
        <div class="hero-grid-container" style="display: grid; grid-template-columns: 1.15fr 460px; gap: 4.5rem; align-items: center;">

            {{-- Left Column: Hero Copy --}}
            <div>
                <div class="badge badge-teal mb-4 animate-fade-up" style="padding: 0.5rem 1rem; font-size: 0.825rem; max-width: 100%; white-space: normal; text-align: center;">
                    <span style="width: 7px; height: 7px; background: var(--clr-teal-light); border-radius: 50%; display: inline-block;"></span>
                    2.500+ Dokter Spesialis Terverifikasi STR & SIP Resmi
                </div>

                <h1 style="margin-bottom: 1.5rem; line-height: 1.25;" class="animate-fade-up">
                    Layanan Kesehatan Daring<br>
                    <span class="text-gradient">Terpadu & Terpercaya</span><br>
                    Untuk Keluarga Anda
                </h1>

                <p style="font-size: 1.1rem; color: var(--txt-body); line-height: 1.85; margin-bottom: 2.75rem; max-width: 580px;" class="animate-fade-up">
                    Akses langsung ke tim medis profesional, analisis indikator kesehatan mandiri, serta pengadaan produk farmasi resmi dengan standar tinggi.
                </p>

                <div class="hero-buttons flex gap-4 flex-wrap mb-12 animate-fade-up">
                    <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-xl">
                        Konsultasi Dokter Sekarang
                    </a>
                    <a href="{{ route('health-check.index') }}" class="btn btn-outline btn-xl">
                        Cek Kesehatan Mandiri
                    </a>
                </div>

                {{-- Stats Grid --}}
                <div class="hero-stats-grid animate-fade-up" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.75rem; padding-top: 2.5rem; border-top: 1px solid var(--bdr-subtle);">
                    <div>
                        <div style="font-size: 1.625rem; font-weight: 800; color: var(--txt-heading);" data-count="2500">0</div>
                        <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.375rem;">Dokter Medis</div>
                    </div>
                    <div>
                        <div style="font-size: 1.625rem; font-weight: 800; color: var(--txt-heading);" data-count="150000">0</div>
                        <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.375rem;">Pasien Aktif</div>
                    </div>
                    <div>
                        <div style="font-size: 1.625rem; font-weight: 800; color: var(--txt-heading);" data-count="98">0</div>
                        <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.375rem;">% Kepuasan</div>
                    </div>
                    <div>
                        <div style="font-size: 1.625rem; font-weight: 800; color: var(--clr-teal-light);">24/7</div>
                        <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.375rem;">Siaga Medis</div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Medical Card Preview --}}
            <div>
                <div class="card" style="box-shadow: var(--shadow-lg), var(--shadow-glow); background: var(--bg-card); border-color: rgba(2, 132, 199, 0.25); padding: 2.25rem; gap: 0;">

                    {{-- Status Bar --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <span class="badge badge-success" style="padding: 0.4rem 0.85rem;">
                            ● Siaga Sesi Online
                        </span>
                        <span style="font-size: 0.8rem; color: var(--txt-muted); font-weight: 500;">
                            Respon &lt; 5 Menit
                        </span>
                    </div>

                    {{-- Doctor Info Bar --}}
                    <div class="flex items-center gap-4 mb-4">
                        <div class="initial-avatar" style="width: 58px; height: 58px; min-width: 58px; min-height: 58px;">
                            AW
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 700; color: var(--txt-heading); font-size: 1.05rem; line-height: 1.4; word-break: break-word;">
                                dr. Andi Wijaya, Sp.PD
                            </div>
                            <div style="font-size: 0.85rem; color: var(--clr-teal-light); font-weight: 600; margin-top: 0.125rem;">
                                Spesialis Penyakit Dalam
                            </div>
                            <div style="font-size: 0.775rem; color: var(--txt-muted); margin-top: 0.25rem;">
                                RS Harapan Utama · 8 Thn Pengalaman
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- Pricing & Ratings --}}
                    <div style="display: flex; flex-direction: column; gap: 0.875rem; margin-bottom: 1.75rem;">
                        <div class="flex-between" style="font-size: 0.9rem;">
                            <span style="color: var(--txt-muted);">Biaya Sesi Konsultasi</span>
                            <span style="font-weight: 800; color: var(--txt-heading); font-size: 1.1rem;">Rp 75.000</span>
                        </div>
                        <div class="flex-between" style="font-size: 0.9rem;">
                            <span style="color: var(--txt-muted);">Tingkat Ulasan Medis</span>
                            <span style="font-weight: 700; color: #F59E0B;">★ 4.9 <small style="color: var(--txt-muted);">(340 ulasan)</small></span>
                        </div>
                    </div>

                    <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-block btn-lg">
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
        <div style="text-align: center; max-width: 680px; margin: 0 auto 4.5rem;">
            <div class="badge badge-primary mb-3">Layanan Terintegrasi</div>
            <h2 style="font-size: 2.25rem; margin-bottom: 1rem;">Fasilitas Kesehatan <span class="text-gradient">Komprehensif</span></h2>
            <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.75;">Dirancang sesuai standar pelayanan medis untuk memberikan rasa aman dan kemudahan bagi setiap pengguna.</p>
        </div>

        <div class="grid grid-3">
            <a href="{{ route('doctors.index') }}" class="card" style="text-decoration: none;">
                <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(2,132,199,0.15); color: var(--clr-brand-light); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.625rem;">Dokter Spesialis Daring</h3>
                <p style="font-size: 0.925rem; color: var(--txt-muted); line-height: 1.7;">Layanan telekonsultasi cepat dengan rekomendasi medis komprehensif dan resep resmi.</p>
            </a>

            <a href="{{ route('health-check.bmi') }}" class="card" style="text-decoration: none;">
                <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(13,148,136,0.15); color: var(--clr-teal-light); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.625rem;">Pemeriksaan Mandiri</h3>
                <p style="font-size: 0.925rem; color: var(--txt-muted); line-height: 1.7;">Alat pengukur indikator kesehatan BMI dan analisis rujukan medis awal.</p>
            </a>

            <a href="{{ route('medicines.index') }}" class="card" style="text-decoration: none;">
                <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(16,185,129,0.15); color: #34D399; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.625rem;">Apotek & Suplemen</h3>
                <p style="font-size: 0.925rem; color: var(--txt-muted); line-height: 1.7;">Pengadaan obat resmi, suplemen kesehatan, dan alat medis terverifikasi BPOM.</p>
            </a>
        </div>
    </div>
</section>

{{-- ── 3. DOKTER MEDIS TERPOPULER ────────────────────── --}}
<section class="section" style="padding-top: 6rem; padding-bottom: 6rem; background: var(--bg-dark);">
    <div class="container">
        <div class="flex-between section-header-wrap mb-10" style="align-items: flex-end; gap: 1.5rem; flex-wrap: wrap;">
            <div>
                <div class="badge badge-teal mb-3" style="display: inline-flex;">Pilihan Spesialisasi</div>
                <h2 style="font-size: 2.25rem; margin-bottom: 0;">Dokter Medis Terpopuler</h2>
            </div>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline">Lihat Semua Dokter →</a>
        </div>

        <div class="grid grid-4">
            @foreach($doctors as $doctor)
                @php
                    $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $doctor->user->name);
                    $words = explode(' ', trim($cleanName));
                    $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div class="doctor-card">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="initial-avatar" style="width: 52px; height: 52px; min-width: 52px; min-height: 52px;">
                                {{ $initials }}
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div class="doctor-name" title="{{ $doctor->user->name }}" style="font-size: 1rem; font-weight: 700; line-height: 1.35;">
                                    {{ $doctor->user->name }}
                                </div>
                                <div class="doctor-spec" style="font-size: 0.825rem; color: var(--clr-teal-light);">
                                    {{ $doctor->specialization->name }}
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--txt-muted); margin-bottom: 1.25rem; line-height: 1.6;">
                            {{ Str::limit($doctor->hospital ?? 'Klinik Utama Medika', 26) }} · {{ $doctor->experience_years }} Thn Pengalaman
                        </div>
                    </div>

                    <div class="doctor-card-footer" style="border-top: 1px solid var(--bdr-subtle); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.05rem;">{{ $doctor->formatted_fee }}</div>
                            <div style="font-size: 0.75rem; color: var(--txt-muted);">per sesi</div>
                        </div>
                        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-primary btn-sm">Konsultasi</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
