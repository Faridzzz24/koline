@extends('layouts.guest')
@section('title', $doctor->user->name)

@section('content')
<style>
.doctor-detail-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2.5rem;
    align-items: start;
    width: 100%;
    box-sizing: border-box;
}

.doctor-hero-header {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.doctor-avatar-box {
    width: 72px;
    height: 72px;
    min-width: 72px;
    min-height: 72px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--clr-brand), var(--clr-teal));
    color: white;
    font-size: 1.6rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(2, 132, 199, 0.3);
}

.doctor-stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    padding: 1.25rem 0;
    border-top: 1px solid var(--bdr-subtle);
    border-bottom: 1px solid var(--bdr-subtle);
    margin-bottom: 1.5rem;
    text-align: center;
}

.doctor-booking-sidebar {
    position: sticky;
    top: 115px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 1024px) {
    .doctor-detail-grid {
        display: flex !important;
        flex-direction: column !important;
        gap: 1.5rem !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .doctor-booking-sidebar {
        position: static !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .doctor-hero-header {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        gap: 1rem !important;
    }

    .doctor-hero-header-info {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }

    .doctor-badges-group {
        justify-content: center !important;
    }

    .doctor-stats-row {
        gap: 0.5rem !important;
        padding: 1rem 0 !important;
    }

    .doctor-stat-val {
        font-size: 1.25rem !important;
    }

    .doctor-stat-lbl {
        font-size: 0.65rem !important;
    }
}
</style>

<div class="page-wrapper" style="padding-top: 120px;">
    <div class="container">

        {{-- Back Navigation --}}
        <a href="{{ route('doctors.index') }}" class="btn btn-ghost btn-sm mb-6" style="display: inline-flex; width: auto;">
            ← Kembali ke Daftar Dokter
        </a>

        @php
            $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $doctor->user->name);
            $words = explode(' ', trim($cleanName));
            $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
        @endphp

        <div class="doctor-detail-grid">

            {{-- LEFT: Doctor Profile --}}
            <div style="display: flex; flex-direction: column; gap: 1.75rem; width: 100%; min-width: 0;">

                {{-- Hero Profile Card --}}
                <div class="card">
                    <div class="doctor-hero-header">
                        <div class="doctor-avatar-box">
                            {{ $initials }}
                        </div>
                        <div class="doctor-hero-header-info" style="flex: 1; min-width: 0;">
                            <div class="doctor-badges-group flex items-center gap-2 flex-wrap mb-2">
                                <h1 style="font-size: clamp(1.4rem, 3.5vw, 1.85rem); font-weight: 800; margin-bottom: 0; color: var(--txt-heading);">{{ $doctor->user->name }}</h1>
                                @if($doctor->is_verified)
                                    <span class="badge badge-teal">✓ Terverifikasi STR</span>
                                @endif
                                @if($doctor->is_available)
                                    <span class="badge badge-success">● Online</span>
                                @else
                                    <span class="badge badge-muted">○ Offline</span>
                                @endif
                            </div>
                            <div style="font-size: 1.05rem; color: var(--clr-teal-light); font-weight: 600; margin-bottom: 0.5rem;">
                                {{ $doctor->specialization->name }}
                            </div>
                            <div class="flex items-center gap-2" style="justify-content: inherit;">
                                <span style="color: #F59E0B; font-weight: 700;">★ {{ $doctor->rating }}</span>
                                <span style="font-size: 0.85rem; color: var(--txt-muted);">({{ $doctor->total_reviews }} ulasan pasien)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Bar --}}
                    <div class="doctor-stats-row">
                        <div>
                            <div class="doctor-stat-val" style="font-size: 1.5rem; font-weight: 800; color: var(--txt-heading);">{{ $doctor->experience_years }} Thn</div>
                            <div class="doctor-stat-lbl" style="font-size: 0.725rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.03em; margin-top: 0.25rem;">Pengalaman Medis</div>
                        </div>
                        <div style="border-left: 1px solid var(--bdr-subtle); border-right: 1px solid var(--bdr-subtle);">
                            <div class="doctor-stat-val" style="font-size: 1.5rem; font-weight: 800; color: var(--txt-heading);">{{ number_format($doctor->total_patients) }}</div>
                            <div class="doctor-stat-lbl" style="font-size: 0.725rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.03em; margin-top: 0.25rem;">Pasien Ditangani</div>
                        </div>
                        <div>
                            <div class="doctor-stat-val" style="font-size: 1.5rem; font-weight: 800; color: var(--txt-heading);">{{ $doctor->total_reviews }}</div>
                            <div class="doctor-stat-lbl" style="font-size: 0.725rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.03em; margin-top: 0.25rem;">Ulasan Medis</div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <h3 style="margin-bottom: 0.75rem; font-size: 1.1rem; font-weight: 700; color: var(--txt-heading);">Profil & Pengalaman Medis</h3>
                    <p style="color: var(--txt-body); line-height: 1.8; font-size: 0.95rem;">{{ $doctor->bio }}</p>
                </div>

                {{-- Medical Info Grid --}}
                <div class="grid grid-2" style="gap: 1.25rem;">
                    <div class="card card-sm">
                        <div style="font-size: 0.75rem; color: var(--txt-muted); margin-bottom: 0.375rem; text-transform: uppercase; letter-spacing: 0.05em;">Pendidikan</div>
                        <div style="font-weight: 600; color: var(--txt-heading);">{{ $doctor->education ?? 'Fakultas Kedokteran Unair' }}</div>
                    </div>
                    <div class="card card-sm">
                        <div style="font-size: 0.75rem; color: var(--txt-muted); margin-bottom: 0.375rem; text-transform: uppercase; letter-spacing: 0.05em;">Tempat Praktik</div>
                        <div style="font-weight: 600; color: var(--txt-heading);">{{ $doctor->hospital ?? 'RS Utama Medika' }}</div>
                    </div>
                    <div class="card card-sm">
                        <div style="font-size: 0.75rem; color: var(--txt-muted); margin-bottom: 0.375rem; text-transform: uppercase; letter-spacing: 0.05em;">No. Surat Tanda Registrasi (STR)</div>
                        <div style="font-weight: 600; color: var(--txt-heading); font-family: monospace;">{{ $doctor->str_number }}</div>
                    </div>
                    <div class="card card-sm">
                        <div style="font-size: 0.75rem; color: var(--txt-muted); margin-bottom: 0.375rem; text-transform: uppercase; letter-spacing: 0.05em;">Spesialisasi</div>
                        <div style="font-weight: 600; color: var(--txt-heading);">{{ $doctor->specialization->name }}</div>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="card">
                    <h3 style="margin-bottom: 1.25rem; font-size: 1.1rem; font-weight: 700; color: var(--txt-heading);">Jadwal Praktik Sesi</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($doctor->schedules as $schedule)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1.25rem; background: var(--bg-surface); border-radius: var(--r-md); border: 1px solid var(--bdr-subtle); flex-wrap: wrap; gap: 0.5rem;">
                                <span style="font-weight: 600; color: var(--txt-heading);">{{ $schedule->day_label }}</span>
                                <span style="color: var(--txt-body); font-size: 0.9rem;">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }} WIB</span>
                                <span class="badge badge-success">Sesi Aktif</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- RIGHT: Booking Form Card --}}
            <div class="doctor-booking-sidebar" x-data="{ durationHours: 1, baseFee: {{ $doctor->consultation_fee }}, selectedTime: '08:00' }">
                <div class="card" style="border-color: rgba(2, 132, 199, 0.35); box-shadow: var(--shadow-lg);">
                    <div style="font-size: 2rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(baseFee * durationHours)">{{ number_format($doctor->consultation_fee, 0, ',', '.') }}</span>
                    </div>
                    <div style="color: var(--txt-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">
                        Total biaya untuk <strong style="color: var(--clr-brand-light);" x-text="durationHours + ' Jam'">1 Jam</strong> telekonsultasi
                    </div>

                    @auth
                        @if(auth()->user()->isPatient())
                            <form action="{{ route('doctors.book', $doctor) }}" method="POST">
                                @csrf
                                <input type="hidden" name="duration_hours" :value="durationHours">

                                {{-- Duration Selector Pills --}}
                                <div class="form-group mb-4">
                                    <label class="form-label flex items-center gap-2" style="font-weight: 700; color: var(--txt-heading);">
                                        <svg width="16" height="16" fill="none" stroke="var(--clr-brand-light)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Pilih Durasi Konsultasi
                                    </label>
                                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-top: 0.25rem;">
                                        <button type="button" 
                                                @click="durationHours = 1"
                                                :class="durationHours === 1 ? 'time-pill-active' : 'time-pill-inactive'"
                                                style="padding: 0.65rem 0.25rem; font-size: 0.8rem; font-weight: 700; border-radius: var(--r-sm); cursor: pointer; text-align: center; width: 100%;">
                                            ⏱️ 1 Jam
                                        </button>
                                        <button type="button" 
                                                @click="durationHours = 2"
                                                :class="durationHours === 2 ? 'time-pill-active' : 'time-pill-inactive'"
                                                style="padding: 0.65rem 0.25rem; font-size: 0.8rem; font-weight: 700; border-radius: var(--r-sm); cursor: pointer; text-align: center; width: 100%;">
                                            ⏱️ 2 Jam
                                        </button>
                                        <button type="button" 
                                                @click="durationHours = 3"
                                                :class="durationHours === 3 ? 'time-pill-active' : 'time-pill-inactive'"
                                                style="padding: 0.65rem 0.25rem; font-size: 0.8rem; font-weight: 700; border-radius: var(--r-sm); cursor: pointer; text-align: center; width: 100%;">
                                            ⏱️ 3 Jam
                                        </button>
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--txt-muted); margin-top: 0.4rem;">*Tarif menyesuaikan durasi (Maksimal 3 Jam)</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label flex items-center gap-2" style="font-weight: 700; color: var(--txt-heading);">
                                        <svg width="16" height="16" fill="none" stroke="var(--clr-brand-light)" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Tanggal Sesi Konsultasi
                                    </label>
                                    <div style="position: relative; cursor: pointer;" onclick="try{ this.querySelector('input').showPicker(); }catch(e){}">
                                        <input type="date" name="consultation_date" class="form-input" min="{{ date('Y-m-d') }}" required style="color-scheme: dark; padding-right: 2.75rem; cursor: pointer; color: #F8FAFC;">
                                        <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: #38BDF8; display: flex; align-items: center;">
                                            <svg width="20" height="20" fill="none" stroke="#38BDF8" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    </div>
                                    @error('consultation_date')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label flex items-center gap-2 mb-2" style="font-weight: 700; color: var(--txt-heading);">
                                        <svg width="16" height="16" fill="none" stroke="var(--clr-brand-light)" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Waktu Sesi Konsultasi
                                    </label>
                                    <input type="hidden" name="consultation_time" :value="selectedTime" required>
                                    
                                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; margin-top: 0.25rem;">
                                        @foreach(['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $time)
                                            <button type="button" 
                                                    @click="selectedTime = '{{ $time }}'"
                                                    :class="selectedTime === '{{ $time }}' ? 'time-pill-active' : 'time-pill-inactive'"
                                                    style="padding: 0.6rem 0.35rem; font-size: 0.8rem; font-weight: 700; border-radius: var(--r-sm); transition: all 0.2s ease; cursor: pointer; text-align: center; width: 100%;">
                                                {{ $time }}
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('consultation_time')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label class="form-label flex items-center gap-2" style="font-weight: 700; color: var(--txt-heading);">
                                        <svg width="16" height="16" fill="none" stroke="var(--clr-brand-light)" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Keluhan Medis Utama
                                    </label>
                                    <textarea name="complaint" class="form-input" rows="4" placeholder="Jelaskan secara rinci keluhan atau gejala yang Anda alami..." required minlength="10"></textarea>
                                    @error('complaint')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-lg" style="width: 100%;">
                                    Mulai Konsultasi Daring →
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info">Hanya akun pasien yang dapat melakukan booking konsultasi.</div>
                        @endif
                    @else
                        <div class="alert alert-info mb-4">Silakan masuk ke akun Anda untuk melakukan booking konsultasi.</div>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block btn-lg" style="width: 100%;">Masuk untuk Booking</a>
                    @endauth

                    <div class="divider"></div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.825rem; color: var(--txt-muted);">
                        <div class="flex items-center gap-2">
                            <span style="color: var(--clr-teal-light);">✓</span> <span>Dokter terverifikasi Kementerian Kesehatan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span style="color: var(--clr-teal-light);">✓</span> <span>Privasi & rekam medis dijamin aman</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
