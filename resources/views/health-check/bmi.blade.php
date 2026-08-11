@extends('layouts.guest')
@section('title', 'Kalkulator BMI & Berat Badan Ideal')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Kalkulator <span class="text-gradient">BMI</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; max-width: 560px; margin: 0 auto; line-height: 1.7;">
                    Body Mass Index (BMI) adalah indikator medis awal untuk mengukur rasio massa tubuh berdasarkan berat dan tinggi badan.
                </p>
            </div>

            <div x-data="bmiApp()" class="card" style="padding: 2.5rem;">
                {{-- Form Input --}}
                <form @submit.prevent="calculate" x-show="!bmi">
                    <div class="grid grid-2" style="gap: 1.75rem; margin-bottom: 2rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Berat Badan (Kilogram)</label>
                            <input type="number" x-model="weight" class="form-input" placeholder="Contoh: 65" min="1" max="500" step="0.1" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Tinggi Badan (Sentimeter)</label>
                            <input type="number" x-model="height" class="form-input" placeholder="Contoh: 170" min="50" max="300" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Usia (Tahun)</label>
                            <input type="number" x-model="age" class="form-input" placeholder="Contoh: 25" min="1" max="120">
                        </div>

                        {{-- Aesthetic Custom Segmented Control for Gender --}}
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Jenis Kelamin</label>
                            <div style="display: flex; gap: 0.5rem; background: var(--bg-input); padding: 0.35rem; border-radius: var(--r-md); border: 1px solid var(--bdr-subtle); height: 48px; align-items: center;">
                                <button type="button" @click="gender = 'male'" :class="gender === 'male' ? 'btn-primary' : 'btn-ghost'" class="btn btn-sm" style="flex: 1; height: 38px; border-radius: var(--r-sm); font-size: 0.875rem;">
                                    Laki-laki
                                </button>
                                <button type="button" @click="gender = 'female'" :class="gender === 'female' ? 'btn-primary' : 'btn-ghost'" class="btn btn-sm" style="flex: 1; height: 38px; border-radius: var(--r-sm); font-size: 0.875rem;">
                                    Perempuan
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" :disabled="calculating" style="height: 50px;">
                        <span x-show="!calculating">Hitung Hasil BMI Saya</span>
                        <span x-show="calculating">Menghitung Indikator...</span>
                    </button>
                </form>

                {{-- Result Screen --}}
                <div x-show="bmi" x-transition style="text-align: center;">
                    <div class="bmi-result-circle" :style="`border-color: ${color}; box-shadow: 0 0 30px ${color}40`" style="margin-bottom: 1.5rem;">
                        <div class="bmi-result-num" :style="`color: ${color}`" x-text="bmi"></div>
                        <div class="bmi-result-label text-muted" style="margin-top: 0.25rem;">SKOR BMI</div>
                    </div>

                    <h2 class="mb-2" x-text="category" :style="`color: ${color}`" style="font-size: 1.75rem; font-weight: 800;"></h2>
                    <p class="text-muted mb-6">Hasil indikator indeks massa tubuh Anda</p>

                    {{-- Scale Gauge --}}
                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle);">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                            <span style="color: #F59E0B;">Underweight</span>
                            <span style="color: #10B981;">Normal</span>
                            <span style="color: #F97316;">Overweight</span>
                            <span style="color: #EF4444;">Obesitas</span>
                        </div>
                        <div style="height: 12px; border-radius: var(--r-full); background: linear-gradient(to right,#F59E0B 0%,#10B981 25%,#F97316 65%,#EF4444 100%); position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; bottom: 0; width: 4px; background: white; border-radius: 2px;" :style="`left: ${Math.min(Math.max((bmi - 10) / 40 * 100, 0), 97)}%`"></div>
                        </div>
                    </div>

                    <form action="{{ route('health-check.bmi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="weight" :value="weight">
                        <input type="hidden" name="height" :value="height">
                        <input type="hidden" name="age" :value="age || 25">
                        <input type="hidden" name="gender" :value="gender">
                        <div class="flex gap-4 justify-center flex-wrap">
                            @auth
                                <button type="submit" class="btn btn-teal">Simpan ke Rekam Medis</button>
                            @endauth
                            <button type="button" @click="reset()" class="btn btn-outline">Hitung Ulang</button>
                            <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Dokter</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Explanatory Cards --}}
            <div class="grid grid-2 mt-8">
                <div class="card card-sm">
                    <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Definisi Indikator BMI</div>
                    <p style="font-size: 0.875rem; color: var(--txt-muted); line-height: 1.7;">BMI dihitung dengan membagi berat badan (kg) dengan kuadrat tinggi badan (m²).</p>
                </div>
                <div class="card card-sm">
                    <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Rujukan Klinis</div>
                    <p style="font-size: 0.875rem; color: var(--txt-muted); line-height: 1.7;">Untuk evaluasi gizi lanjutan, disarankan berkonsultasi dengan dokter spesialis gizi.</p>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function bmiApp() {
    return {
        weight: '', height: '', age: '', gender: 'male',
        bmi: null, category: '', color: '', calculating: false,

        calculate() {
            if (!this.weight || !this.height) return;
            this.calculating = true;
            setTimeout(() => {
                const h = this.height / 100;
                this.bmi = (this.weight / (h * h)).toFixed(1);
                if (this.bmi < 18.5) { this.category = 'Kurus (Underweight)'; this.color = '#F59E0B'; }
                else if (this.bmi < 25) { this.category = 'Ideal (Normal)'; this.color = '#10B981'; }
                else if (this.bmi < 30) { this.category = 'Gemuk (Overweight)'; this.color = '#F97316'; }
                else { this.category = 'Obesitas'; this.color = '#EF4444'; }
                this.calculating = false;
            }, 300);
        },
        reset() {
            this.bmi = null;
        }
    }
}
</script>
@endpush
@endsection
