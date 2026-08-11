@extends('layouts.guest')
@section('title', 'Tes Gangguan Kecemasan')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="kecemasanApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Skrining <span class="text-gradient">Tingkat Kecemasan</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Evaluasi klinis tingkat kecemasan & rasa khawatir berlebih berbasis indikator GAD (Generalized Anxiety Disorder).
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                <div x-show="!submitted">
                    <form @submit.prevent="calculateRisk()">
                        <div style="display: flex; flex-direction: column; gap: 1.75rem; margin-bottom: 2rem;">

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">1. Seberapa sering Anda merasa gugup, cemas, atau gelisah berlebihan?</label>
                                <select x-model.number="q1" class="form-select">
                                    <option value="0">Tidak Sama Sekali (0)</option>
                                    <option value="1">Beberapa Hari (1)</option>
                                    <option value="2">Lebih dari Separuh Hari (2)</option>
                                    <option value="3">Hampir Setiap Hari (3)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">2. Seberapa sering Anda sulit menghentikan atau mengontrol rasa khawatir?</label>
                                <select x-model.number="q2" class="form-select">
                                    <option value="0">Tidak Sama Sekali (0)</option>
                                    <option value="1">Beberapa Hari (1)</option>
                                    <option value="2">Lebih dari Separuh Hari (2)</option>
                                    <option value="3">Hampir Setiap Hari (3)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">3. Seberapa sering Anda merasa sangat gelisah hingga sulit duduk tenang?</label>
                                <select x-model.number="q3" class="form-select">
                                    <option value="0">Tidak Sama Sekali (0)</option>
                                    <option value="1">Beberapa Hari (1)</option>
                                    <option value="2">Lebih dari Separuh Hari (2)</option>
                                    <option value="3">Hampir Setiap Hari (3)</option>
                                </select>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Tampilkan Skor Kecemasan GAD
                        </button>
                    </form>
                </div>

                {{-- Result --}}
                <div x-show="submitted" x-transition style="text-align: center;">
                    <div class="badge badge-teal mb-4" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Hasil Indikator GAD Assessment</div>

                    <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 1rem;" :style="`color: ${resultColor}`" x-text="resultLabel"></h2>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle); text-align: left;">
                        <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Panduan Ketenangan & Rujukan:</div>
                        <p style="font-size: 0.925rem; color: var(--txt-body); line-height: 1.7; margin-bottom: 0;" x-text="resultAdvice"></p>
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="submitted = false" class="btn btn-outline">Ulangi Tes</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Dokter Medis →</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function kecemasanApp() {
    return {
        q1: 0, q2: 0, q3: 0,
        submitted: false,
        resultLabel: '', resultColor: '', resultAdvice: '',

        calculateRisk() {
            const score = this.q1 + this.q2 + this.q3;
            this.submitted = true;

            if (score <= 2) {
                this.resultLabel = 'Kecemasan Tingkat Minimal';
                this.resultColor = '#10B981';
                this.resultAdvice = 'Tingkat kecemasan Anda berada pada batas wajar dan aman. Latihan relaksasi otot dan mindfulness dapat membantu menjaga stabilitas emosional.';
            } else if (score <= 5) {
                this.resultLabel = 'Kecemasan Tingkat Sedang';
                this.resultColor = '#F59E0B';
                this.resultAdvice = 'Rasa khawatir mulai cukup mengganggu konsentrasi harian Anda. Cobalah teknik olah napas teratur dan batasi konsumsi kafein berlebih.';
            } else {
                this.resultLabel = 'Kecemasan Tingkat Tinggi';
                this.resultColor = '#EF4444';
                this.resultAdvice = 'Indikator menunjukkan rasa cemas yang signifikan. Sangat disarankan berkonsultasi dengan Dokter atau Psikolog KoLine untuk evaluasi lanjutan.';
            }
        }
    }
}
</script>
@endpush
@endsection
