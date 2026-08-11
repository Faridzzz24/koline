@extends('layouts.guest')
@section('title', 'Pusat Cek Kesehatan Mandiri')

@section('content')
<div class="page-wrapper">
    <div class="container">

        {{-- Header --}}
        <div style="text-align: center; max-width: 720px; margin: 0 auto 4.5rem;">
            <div class="badge badge-teal mb-3" style="padding: 0.4rem 1rem;">Pusat Diagnostik Mandiri</div>
            <h1 style="margin-bottom: 1rem;">Cek <span class="text-gradient">Kesehatan Mandiri</span></h1>
            <p style="color: var(--txt-muted); font-size: 1.1rem; line-height: 1.8;">
                10 Alat ukur indikator medis interaktif berbasis standar kesehatan profesional untuk pemantauan fisik & mental secara berkala.
            </p>
        </div>

        {{-- 10 Tools Grid --}}
        <div class="grid grid-3 health-tools-grid">

            {{-- 1. Cek Stres --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(2, 132, 199, 0.15); color: var(--clr-brand-light); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-4a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Cek Stres</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Ukur tingkat beban mental & stres harian dengan kuesioner medis interaktif.</p>
                </div>
                <a href="{{ route('health-check.stres') }}" class="btn btn-primary btn-block">Cek Stres Saya →</a>
            </div>

            {{-- 2. Kalkulator BMI --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(13, 148, 136, 0.15); color: var(--clr-teal-light); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6l3 18h12l3-18H3z"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Kalkulator BMI</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Hitung indeks massa tubuh (BMI) & kisaran berat badan ideal Anda.</p>
                </div>
                <a href="{{ route('health-check.bmi') }}" class="btn btn-primary btn-block">Hitung BMI Saya →</a>
            </div>

            {{-- 3. Risiko Jantung --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(239, 68, 68, 0.15); color: #FCA5A5; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Risiko Jantung</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Skrining awal tingkat risiko kardiovaskular berdasarkan pola hidup & tekanan darah.</p>
                </div>
                <a href="{{ route('health-check.jantung') }}" class="btn btn-primary btn-block">Cek Risiko Jantung →</a>
            </div>

            {{-- 4. Risiko Diabetes --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(245, 158, 11, 0.15); color: #FBBF24; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Risiko Diabetes</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Skrining indikator gula darah & risiko diabetes tipe 2 (metode FINDRISC).</p>
                </div>
                <a href="{{ route('health-check.diabetes') }}" class="btn btn-primary btn-block">Cek Risiko Diabetes →</a>
            </div>

            {{-- 5. Tes Depresi --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(168, 85, 247, 0.15); color: #C084FC; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Tes Depresi</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Skrining kesehatan suasana hati & tingkat indikator depresi (PHQ Standard).</p>
                </div>
                <a href="{{ route('health-check.depresi') }}" class="btn btn-primary btn-block">Mulai Tes Depresi →</a>
            </div>

            {{-- 6. Tes Gangguan Kecemasan --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(59, 130, 246, 0.15); color: #60A5FA; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Tes Kecemasan</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Skrining tingkat rasa cemas & kelelahan emosional (Skala GAD-7).</p>
                </div>
                <a href="{{ route('health-check.kecemasan') }}" class="btn btn-primary btn-block">Mulai Tes Kecemasan →</a>
            </div>

            {{-- 7. Kalender Menstruasi --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(236, 72, 153, 0.15); color: #F472B6; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Kalender Menstruasi</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Hitung perkiraan tanggal siklus haid berikutnya, masa subur, & hari ovulasi.</p>
                </div>
                <a href="{{ route('health-check.menstruasi') }}" class="btn btn-primary btn-block">Hitung Siklus Haid →</a>
            </div>

            {{-- 8. Pengingat Obat --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(16, 185, 129, 0.15); color: #34D399; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Pengingat Obat</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Kelola jadwal minum obat harian & alarm dosis obat dengan teratur.</p>
                </div>
                <a href="{{ route('health-check.pengingat-obat') }}" class="btn btn-primary btn-block">Kelola Jadwal Obat →</a>
            </div>

            {{-- 9. Kalender Kehamilan --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem;">
                <div>
                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(99, 102, 241, 0.15); color: #818CF8; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Kalender Kehamilan</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.65;">Kalkulator Hari Perkiraan Lahir (HPL) & usia kehamilan (Metode Naegele).</p>
                </div>
                <a href="{{ route('health-check.kehamilan') }}" class="btn btn-primary btn-block">Kalkulator HPL →</a>
            </div>

            {{-- 10. Donasi Medis --}}
            <div class="card" style="justify-content: space-between; gap: 1.5rem; grid-column: span 3;">
                <div style="display: flex; align-items: center; gap: 1.75rem; flex-wrap: wrap;">
                    <div style="width: 60px; height: 60px; border-radius: var(--r-md); background: rgba(239, 68, 68, 0.15); color: #FCA5A5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <div style="flex: 1; min-width: 280px;">
                        <h3 style="font-size: 1.35rem; margin-bottom: 0.375rem;">Donasi Medis KoLine Peduli</h3>
                        <p style="color: var(--txt-muted); font-size: 0.95rem; margin-bottom: 0;">Bantu pembiayaan pengobatan & pengadaan alat medis bagi pasien yang membutuhkan.</p>
                    </div>
                    <a href="{{ route('health-check.donasi') }}" class="btn btn-teal btn-lg" style="height: 48px;">Lihat Program Donasi Medis →</a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
