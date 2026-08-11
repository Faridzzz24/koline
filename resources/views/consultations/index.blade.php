@extends('layouts.app')
@section('title', auth()->user()->isDoctor() ? 'Daftar Antrean Pasien | KoLine' : 'Daftar Konsultasi Saya | KoLine')

@section('content')
<div style="max-width: 1040px; margin: 0 auto;">

    <div class="flex-between items-center mb-8" style="flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 0.25rem;">
                {{ auth()->user()->isDoctor() ? 'Daftar Antrean Pasien' : 'Konsultasi Medis Saya' }}
            </h1>
            <div style="font-size: 0.95rem; color: var(--txt-muted);">
                {{ auth()->user()->isDoctor() ? 'Kelola permintaan janji medis dan sesi telekonsultasi aktif pasien Anda' : 'Riwayat dan sesi telekonsultasi aktif Anda dengan dokter spesialis' }}
            </div>
        </div>
        @if(auth()->user()->isPatient())
            <a href="{{ route('doctors.index') }}" class="btn btn-primary">+ Konsultasi Baru</a>
        @else
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline">← Kembali ke Dashboard</a>
        @endif
    </div>

    @if($consultations->isEmpty())
        <div class="card" style="text-align: center; padding: 4.5rem 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">💬</div>
            @if(auth()->user()->isDoctor())
                <h3 style="margin-bottom: 0.5rem; color: var(--txt-heading);">Belum Ada Antrean Pasien</h3>
                <p style="color: var(--txt-muted); max-width: 440px; margin: 0 auto 1.5rem;">Saat ini belum ada permintaan janji konsultasi atau percakapan masuk dari pasien.</p>
                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary" style="display: inline-flex; width: auto;">Kembali ke Dashboard Dokter</a>
            @else
                <h3 style="margin-bottom: 0.5rem; color: var(--txt-heading);">Belum Ada Konsultasi</h3>
                <p style="color: var(--txt-muted); max-width: 440px; margin: 0 auto 1.5rem;">Mulai sesi konsultasi online secara cepat & tepercaya dengan dokter spesialis kami.</p>
                <a href="{{ route('doctors.index') }}" class="btn btn-primary" style="display: inline-flex; width: auto;">Cari Dokter Sekarang →</a>
            @endif
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @foreach($consultations as $consultation)
                @php
                    $targetUser = auth()->user()->isPatient() ? $consultation->doctor->user : $consultation->patient;
                    $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $targetUser->name);
                    $words = explode(' ', trim($cleanName));
                    $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                @endphp
                <div class="card" style="padding: 1.5rem 1.75rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem;">
                        <div class="flex items-center gap-4">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(2, 132, 199, 0.15); color: var(--clr-brand-light); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; border: 1px solid rgba(2, 132, 199, 0.3); flex-shrink: 0;">
                                {{ $initials }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: var(--txt-heading); font-size: 1.05rem;">
                                    @if(auth()->user()->isPatient())
                                        {{ $consultation->doctor->user->name }}
                                        <span style="font-size: 0.825rem; color: var(--clr-teal-light); font-weight: 600; margin-left: 0.5rem;">{{ $consultation->doctor->specialization->name }}</span>
                                    @else
                                        {{ $consultation->patient->name }}
                                        <span style="font-size: 0.825rem; color: var(--txt-muted); font-weight: 500; margin-left: 0.5rem;">(Pasien)</span>
                                    @endif
                                </div>
                                <div style="font-size: 0.85rem; color: var(--txt-muted); margin-top: 0.25rem;">
                                    📅 {{ $consultation->consultation_date->format('d M Y') }} · {{ substr($consultation->consultation_time, 0, 5) }} WIB
                                </div>
                                <div style="font-size: 0.875rem; color: var(--txt-body); margin-top: 0.375rem;">
                                    Keluhan: {{ Str::limit($consultation->complaint, 80) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="badge badge-{{ $consultation->status_color }}">
                                {{ $consultation->status_label }}
                            </span>
                            <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-outline btn-sm">Buka Ruang Sesi →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($consultations->hasPages())
            <div style="margin-top: 3rem; display: flex; justify-content: center;">
                {{ $consultations->links('vendor.pagination.custom') }}
            </div>
        @endif
    @endif

</div>
@endsection
