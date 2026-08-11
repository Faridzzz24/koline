@extends('layouts.guest')
@section('title', 'Analisis Gejala Penyakit')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 760px; margin: 0 auto;">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Analisis <span class="text-gradient">Gejala Penyakit</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; max-width: 600px; margin: 0 auto; line-height: 1.7;">
                    Pilih indikator keluhan yang dirasakan untuk mendapatkan informasi rujukan awal penyakit dan saran medis.
                </p>
            </div>

            <div x-data="symptomApp()">
                {{-- Progress Bar --}}
                <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 2.5rem;">
                    @foreach(['1. Pilih Indikator Gejala', '2. Durasi & Keparahan', '3. Hasil Analisis'] as $i => $label)
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--txt-muted);" :style="step >= {{ $i + 1 }} ? 'color: var(--clr-brand-light); font-weight: 700;' : ''">
                            {{ $label }}
                        </div>
                    @endforeach
                </div>

                {{-- Step 1: Select Symptoms --}}
                <div x-show="step === 1" class="card" style="padding: 2.5rem;">
                    <h3 style="margin-bottom: 0.5rem;">Indikator Keluhan Medis</h3>
                    <p style="color: var(--txt-muted); font-size: 0.925rem; margin-bottom: 1.75rem;">Pilih satu atau beberapa gejala yang dialami saat ini.</p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                        <template x-for="symptom in symptoms" :key="symptom.id">
                            <button type="button"
                                @click="toggleSymptom(symptom.id)"
                                :class="hasSymptom(symptom.id) ? 'btn-primary' : 'btn-outline'"
                                class="btn"
                                style="justify-content: center; padding: 0.875rem 1rem; border-radius: var(--r-md); font-size: 0.875rem;">
                                <span x-text="symptom.label"></span>
                            </button>
                        </template>
                    </div>

                    <button @click="nextStep()" class="btn btn-primary btn-block btn-lg" :disabled="selectedSymptoms.length === 0" style="height: 50px;">
                        Lanjut ke Durasi Gejala →
                    </button>
                </div>

                {{-- Step 2: Duration & Severity --}}
                <div x-show="step === 2" class="card" style="padding: 2.5rem;">
                    <h3 style="margin-bottom: 1.5rem;">Detail Durasi & Intensitas</h3>

                    <div class="form-group mb-6">
                        <label class="form-label">Durasi Gejala Dialami</label>
                        <select x-model="duration" class="form-select">
                            <option value="">Pilih durasi waktu...</option>
                            <option value="kurang_1_hari">Kurang dari 24 Jam</option>
                            <option value="1_3_hari">1 - 3 Hari</option>
                            <option value="3_7_hari">3 - 7 Hari</option>
                            <option value="1_2_minggu">1 - 2 Minggu</option>
                            <option value="lebih_2_minggu">Lebih dari 2 Minggu</option>
                        </select>
                    </div>

                    <div class="form-group mb-8">
                        <label class="form-label">Tingkat Intensitas / Keparahan</label>
                        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                            @foreach([
                                ['val' => 'mild', 'label' => 'Intensitas Ringan', 'desc' => 'Gejala tidak mengganggu aktivitas harian'],
                                ['val' => 'moderate', 'label' => 'Intensitas Sedang', 'desc' => 'Gejala cukup mengganggu aktivitas'],
                                ['val' => 'severe', 'label' => 'Intensitas Berat', 'desc' => 'Gejala sangat mengganggu dan membutuhkan perhatian medis'],
                            ] as $sev)
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1.125rem 1.5rem; border-radius: var(--r-md); cursor: pointer; transition: all var(--tr-fast);"
                                       :style="severity === '{{ $sev['val'] }}' ? 'background: rgba(2,132,199,0.12); border: 1.5px solid var(--clr-brand);' : 'background: var(--bg-surface); border: 1px solid var(--bdr-subtle);'">
                                    <input type="radio" x-model="severity" value="{{ $sev['val'] }}" style="accent-color: var(--clr-brand);">
                                    <div>
                                        <div style="font-weight: 700; color: var(--txt-heading); font-size: 0.95rem;">{{ $sev['label'] }}</div>
                                        <div style="font-size: 0.825rem; color: var(--txt-muted); margin-top: 0.125rem;">{{ $sev['desc'] }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button @click="prevStep()" class="btn btn-outline" style="height: 50px; padding: 0 1.75rem;">← Kembali</button>
                        <form action="{{ route('health-check.symptom.store') }}" method="POST" style="flex: 1;">
                            @csrf
                            <template x-for="s in selectedSymptoms">
                                <input type="hidden" name="symptoms[]" :value="s">
                            </template>
                            <input type="hidden" name="duration" :value="duration">
                            <input type="hidden" name="severity" :value="severity">
                            <button type="submit" class="btn btn-primary btn-block btn-lg" :disabled="!duration || !severity" style="height: 50px;">
                                Tampilkan Analisis Medis
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-8" style="font-size: 0.875rem;">
                Disclaimer: Alat ini hanya memberikan analisis rujukan awal medis. Selalu hubungi dokter spesialis untuk evaluasi yang tepat.
            </div>

        </div>
    </div>
</div>
@endsection
