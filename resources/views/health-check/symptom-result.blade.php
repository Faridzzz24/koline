@extends('layouts.guest')
@section('title', 'Hasil Analisis Gejala')
@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <div class="badge badge-teal mb-3">Laporan Analisis Medis</div>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Hasil <span class="text-gradient">Analisis Gejala</span></h1>
                <div class="badge {{ $severity === 'severe' ? 'badge-danger' : ($severity === 'moderate' ? 'badge-warning' : 'badge-success') }}" style="font-size: 0.85rem; padding: 0.4rem 1rem;">
                    Tingkat Intensitas: {{ $severity === 'severe' ? 'Berat' : ($severity === 'moderate' ? 'Sedang' : 'Ringan') }}
                </div>
            </div>

            {{-- Symptoms List --}}
            <div class="card mb-6">
                <h3 style="margin-bottom: 1rem;">Indikator Keluhan Dialami</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($symptoms as $s)
                        <span class="badge badge-primary" style="font-size: 0.825rem; padding: 0.4rem 0.875rem;">{{ str_replace('_', ' ', ucfirst($s)) }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Possible Conditions --}}
            <div class="card mb-6">
                <h3 style="margin-bottom: 1.25rem;">Rujukan Kemungkinan Kondisi</h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($result['possible_conditions'] as $i => $condition)
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: var(--bg-surface); border-radius: var(--r-md); border: 1px solid var(--bdr-subtle);">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--clr-brand); display: flex; align-items: center; justify-content: center; font-weight: 800; color: white; font-size: 0.85rem; flex-shrink: 0;">{{ $i + 1 }}</div>
                            <div style="font-weight: 600; color: var(--txt-heading); font-size: 0.95rem;">{{ $condition }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recommendations --}}
            <div class="card mb-8">
                <h3 style="margin-bottom: 1.25rem;">Saran & Langkah Penanganan</h3>
                <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                    @foreach($result['recommendations'] as $rec)
                        <div style="display: flex; align-items: flex-start; gap: 0.875rem; padding-bottom: 0.875rem; border-bottom: 1px solid var(--bdr-subtle);">
                            <span style="color: var(--clr-teal-light); font-weight: 800;">✓</span>
                            <span style="color: var(--txt-body); font-size: 0.925rem; line-height: 1.7;">{{ $rec }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- CTA Card --}}
            <div class="card" style="text-align: center; background: var(--bg-surface); padding: 3rem 2rem;">
                <h3 style="margin-bottom: 0.5rem;">Konsultasikan Langsung dengan Dokter</h3>
                <p style="color: var(--txt-muted); max-width: 500px; margin: 0 auto 2rem; font-size: 0.95rem;">Untuk diagnosis medis yang akurat dan resep obat yang tepat, disarankan berkonsultasi dengan dokter spesialis.</p>
                <div class="flex-center gap-4 flex-wrap">
                    <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-lg">Konsultasi Dokter Sekarang</a>
                    <a href="{{ route('health-check.symptom') }}" class="btn btn-outline">Analisis Ulang</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
