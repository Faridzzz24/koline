@extends('layouts.guest')
@section('title', 'Tes Depresi & Kesehatan Emosional')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="depresiApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Skrining <span class="text-gradient">Kesehatan Emosional</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Kuesioner penilai kondisi suasana hati berbasis instrumen klinis PHQ (Patient Health Questionnaire).
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                <div x-show="!submitted">
                    <form @submit.prevent="calculateRisk()">
                        <div style="display: flex; flex-direction: column; gap: 1.75rem; margin-bottom: 2rem;">

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">1. Selama 2 minggu terakhir, seberapa sering Anda merasa kurang berminat atau tidak menikmati aktivitas harian?</label>
                                <select x-model.number="q1" class="form-select">
                                    <option value="0">Tidak Sama Sekali (0)</option>
                                    <option value="1">Beberapa Hari (1)</option>
                                    <option value="2">Lebih dari Separuh Hari (2)</option>
                                    <option value="3">Hampir Setiap Hari (3)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">2. Selama 2 minggu terakhir, seberapa sering Anda merasa murung, sedih, atau putus asa?</label>
                                <select x-model.number="q2" class="form-select">
                                    <option value="0">Tidak Sama Sekali (0)</option>
                                    <option value="1">Beberapa Hari (1)</option>
                                    <option value="2">Lebih dari Separuh Hari (2)</option>
                                    <option value="3">Hampir Setiap Hari (3)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.5rem;">3. Seberapa sering Anda merasa lelah berlebihan atau kekurangan energi?</label>
                                <select x-model.number="q3" class="form-select">
                                    <option value="0">Tidak Sama Sekali (0)</option>
                                    <option value="1">Beberapa Hari (1)</option>
                                    <option value="2">Lebih dari Separuh Hari (2)</option>
                                    <option value="3">Hampir Setiap Hari (3)</option>
                                </select>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Lihat Laporan Evaluasi Suasana Hati
                        </button>
                    </form>
                </div>

                {{-- Result --}}
                <div x-show="submitted" x-transition style="text-align: center;">
                    <div class="badge badge-teal mb-4" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Evaluasi PHQ Mental Assessment</div>

                    <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 1rem;" :style="`color: ${resultColor}`" x-text="resultLabel"></h2>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle); text-align: left;">
                        <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Catatan Kesehatan Jiwa:</div>
                        <p style="font-size: 0.925rem; color: var(--txt-body); line-height: 1.7; margin-bottom: 0;" x-text="resultAdvice"></p>
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="submitted = false" class="btn btn-outline">Ulangi Skrining</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Psikolog / Psikiater →</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function depresiApp() {
    return {
        q1: 0, q2: 0, q3: 0,
        submitted: false,
        resultLabel: '', resultColor: '', resultAdvice: '',

        calculateRisk() {
            const score = this.q1 + this.q2 + this.q3;
            this.submitted = true;

            if (score <= 2) {
                this.resultLabel = 'Indikator Suasana Hati Sehat';
                this.resultColor = '#10B981';
                this.resultAdvice = 'Kondisi kebugaran emosional Anda berada dalam rentang yang sehat. Tetap jaga pola tidur teratur, interaksi sosial hangat, dan hobi yang menyenangkan.';
            } else if (score <= 5) {
                this.resultLabel = 'Indikator Kejenuhan / Depresi Ringan';
                this.resultColor = '#F59E0B';
                this.resultAdvice = 'Anda menunjukkan tanda-tanda kelelahan emosional atau suasana hati murung sementara. Luangkan waktu untuk relaksasi, bicarakan perasaan Anda dengan kerabat tepercaya.';
            } else {
                this.resultLabel = 'Indikator Gejala Depresi Perlu Perhatian';
                this.resultColor = '#EF4444';
                this.resultAdvice = 'Skor menunjukkan beban emosional yang membutuhkan bantuan profesional. Sangat disarankan berkonsultasi secara aman & rahasia dengan Psikolog/Psikiater KoLine.';
            }
        }
    }
}
</script>
@endpush
@endsection
