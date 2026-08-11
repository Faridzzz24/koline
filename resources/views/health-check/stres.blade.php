@extends('layouts.guest')
@section('title', 'Cek Stres & Tingkat Beban Mental')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="stresApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Skrining <span class="text-gradient">Tingkat Stres</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Evaluasi tingkat beban emosional & stres harian Anda berbasis indikator kesehatan mental standar (DASS-21 Subscale).
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                {{-- Form Screen --}}
                <div x-show="!submitted">
                    <form @submit.prevent="calculateScore()">
                        <div style="display: flex; flex-direction: column; gap: 2rem; margin-bottom: 2.5rem;">

                            {{-- Question 1 --}}
                            <div>
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.875rem;">1. Seberapa sering Anda merasa tegang atau sulit merasa tenang?</label>
                                <div class="grid grid-2" style="gap: 0.75rem;">
                                    @foreach([
                                        ['val' => 0, 'label' => 'Tidak Pernah'],
                                        ['val' => 1, 'label' => 'Kadang-kadang'],
                                        ['val' => 2, 'label' => 'Sering'],
                                        ['val' => 3, 'label' => 'Sangat Sering']
                                    ] as $opt)
                                        <button type="button" @click="q1 = {{ $opt['val'] }}" :class="q1 === {{ $opt['val'] }} ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px; font-size: 0.875rem;">
                                            {{ $opt['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Question 2 --}}
                            <div>
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.875rem;">2. Seberapa sering Anda merasa kewalahan dengan aktivitas harian?</label>
                                <div class="grid grid-2" style="gap: 0.75rem;">
                                    @foreach([
                                        ['val' => 0, 'label' => 'Tidak Pernah'],
                                        ['val' => 1, 'label' => 'Kadang-kadang'],
                                        ['val' => 2, 'label' => 'Sering'],
                                        ['val' => 3, 'label' => 'Sangat Sering']
                                    ] as $opt)
                                        <button type="button" @click="q2 = {{ $opt['val'] }}" :class="q2 === {{ $opt['val'] }} ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px; font-size: 0.875rem;">
                                            {{ $opt['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Question 3 --}}
                            <div>
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.875rem;">3. Seberapa sering Anda mudah marah atau gelisah akibat hal kecil?</label>
                                <div class="grid grid-2" style="gap: 0.75rem;">
                                    @foreach([
                                        ['val' => 0, 'label' => 'Tidak Pernah'],
                                        ['val' => 1, 'label' => 'Kadang-kadang'],
                                        ['val' => 2, 'label' => 'Sering'],
                                        ['val' => 3, 'label' => 'Sangat Sering']
                                    ] as $opt)
                                        <button type="button" @click="q3 = {{ $opt['val'] }}" :class="q3 === {{ $opt['val'] }} ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px; font-size: 0.875rem;">
                                            {{ $opt['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Question 4 --}}
                            <div>
                                <label class="form-label" style="font-size: 1rem; margin-bottom: 0.875rem;">4. Apakah Anda mengalami gangguan tidur akibat terlalu banyak pikiran?</label>
                                <div class="grid grid-2" style="gap: 0.75rem;">
                                    @foreach([
                                        ['val' => 0, 'label' => 'Tidak Pernah'],
                                        ['val' => 1, 'label' => 'Kadang-kadang'],
                                        ['val' => 2, 'label' => 'Sering'],
                                        ['val' => 3, 'label' => 'Sangat Sering']
                                    ] as $opt)
                                        <button type="button" @click="q4 = {{ $opt['val'] }}" :class="q4 === {{ $opt['val'] }} ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px; font-size: 0.875rem;">
                                            {{ $opt['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Tampilkan Hasil Analisis Stres
                        </button>
                    </form>
                </div>

                {{-- Result Screen --}}
                <div x-show="submitted" x-transition style="text-align: center;">
                    <div class="badge badge-teal mb-4" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Laporan Hasil Evaluasi</div>

                    <div style="font-size: 3.5rem; font-weight: 900; margin-bottom: 0.25rem;" :style="`color: ${resultColor}`" x-text="score + ' / 12'"></div>
                    <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1rem;" :style="`color: ${resultColor}`" x-text="resultLabel"></h2>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle); text-align: left;">
                        <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Rekomendasi Medis & Relaksasi:</div>
                        <p style="font-size: 0.925rem; color: var(--txt-body); line-height: 1.7; margin-bottom: 0;" x-text="resultAdvice"></p>
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="resetForm()" class="btn btn-outline">Cek Ulang</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Psikiater / Psikolog →</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function stresApp() {
    return {
        q1: 0, q2: 0, q3: 0, q4: 0,
        submitted: false,
        score: 0,
        resultLabel: '',
        resultColor: '',
        resultAdvice: '',

        calculateScore() {
            this.score = this.q1 + this.q2 + this.q3 + this.q4;
            this.submitted = true;

            if (this.score <= 3) {
                this.resultLabel = 'Tingkat Stres Normal / Rendah';
                this.resultColor = '#10B981';
                this.resultAdvice = 'Kondisi beban emosional Anda berada dalam batas normal yang stabil. Pertahankan pola hidup sehat, olahraga teratur, dan istirahat yang cukup.';
            } else if (this.score <= 7) {
                this.resultLabel = 'Tingkat Stres Sedang';
                this.resultColor = '#F59E0B';
                this.resultAdvice = 'Anda mulai merasakan beban emosional yang mengganggu produktivitas. Disarankan mengambil waktu jeda (break), melakukan pernapasan dalam, dan mengatur prioritas harian.';
            } else {
                this.resultLabel = 'Tingkat Stres Tinggi';
                this.resultColor = '#EF4444';
                this.resultAdvice = 'Kondisi stres Anda tergolong tinggi dan membutuhkan perhatian serius. Disarankan untuk berkonsultasi langsung dengan psikiater atau psikolog klinis KoLine.';
            }
        },
        resetForm() {
            this.q1 = 0; this.q2 = 0; this.q3 = 0; this.q4 = 0;
            this.submitted = false;
        }
    }
}
</script>
@endpush
@endsection
