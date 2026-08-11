@extends('layouts.guest')
@section('title', 'Skrining Risiko Diabetes')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="diabetesApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Skrining <span class="text-gradient">Risiko Diabetes</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Evaluasi risiko potensi diabetes tipe 2 berbasis indikator klinis FINDRISC (Finnish Diabetes Risk Score).
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                <div x-show="!submitted">
                    <form @submit.prevent="calculateRisk()">
                        <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2rem;">

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">1. Berapa usia Anda?</label>
                                <select x-model.number="qAge" class="form-select">
                                    <option value="0">&lt; 45 Tahun (0 poin)</option>
                                    <option value="2">45 - 54 Tahun (2 poin)</option>
                                    <option value="3">55 - 64 Tahun (3 poin)</option>
                                    <option value="4">&gt; 64 Tahun (4 poin)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">2. Lingkar Pinggang</label>
                                <select x-model.number="qWaist" class="form-select">
                                    <option value="0">Normal (Laki &lt;90cm / Wanita &lt;80cm) (0 poin)</option>
                                    <option value="3">Sedang (Laki 90-102cm / Wanita 80-88cm) (3 poin)</option>
                                    <option value="4">Besar (Laki &gt;102cm / Wanita &gt;88cm) (4 poin)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">3. Apakah ada anggota keluarga kandung yang mengidap diabetes?</label>
                                <select x-model.number="qFamily" class="form-select">
                                    <option value="0">Tidak Ada (0 poin)</option>
                                    <option value="3">Kakek/Nenek/Paman/Bibi (3 poin)</option>
                                    <option value="5">Orang Tua / Saudara Kandung / Anak (5 poin)</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">4. Riwayat Kadar Gula Darah Tinggi</label>
                                <select x-model.number="qSugar" class="form-select">
                                    <option value="0">Tidak Pernah / Normal (0 poin)</option>
                                    <option value="5">Pernah Tinggi Saat Pemeriksaan Rutin (5 poin)</option>
                                </select>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Hitung Skor Risiko Diabetes
                        </button>
                    </form>
                </div>

                {{-- Result --}}
                <div x-show="submitted" x-transition style="text-align: center;">
                    <div class="badge badge-teal mb-4" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Skor FINDRISC Risk Assessment</div>

                    <div style="font-size: 3.5rem; font-weight: 900; margin-bottom: 0.25rem;" :style="`color: ${resultColor}`" x-text="score + ' Poin'"></div>
                    <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1rem;" :style="`color: ${resultColor}`" x-text="resultLabel"></h2>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle); text-align: left;">
                        <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Saran Gaya Hidup & Pemeriksaan Lab:</div>
                        <p style="font-size: 0.925rem; color: var(--txt-body); line-height: 1.7; margin-bottom: 0;" x-text="resultAdvice"></p>
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="submitted = false" class="btn btn-outline">Cek Ulang</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Dokter Endokrin →</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function diabetesApp() {
    return {
        qAge: 0, qWaist: 0, qFamily: 0, qSugar: 0,
        submitted: false, score: 0,
        resultLabel: '', resultColor: '', resultAdvice: '',

        calculateRisk() {
            this.score = this.qAge + this.qWaist + this.qFamily + this.qSugar;
            this.submitted = true;

            if (this.score < 7) {
                this.resultLabel = 'Risiko Diabetes Sangat Rendah';
                this.resultColor = '#10B981';
                this.resultAdvice = 'Kemungkinan berkembang diabetes tipe 2 dalam 10 tahun ke depan diperkirakan di bawah 1%. Pertahankan asupan nutrisi seimbang dan kurangi minuman manis berlebih.';
            } else if (this.score <= 11) {
                this.resultLabel = 'Risiko Diabetes Sedang';
                this.resultColor = '#F59E0B';
                this.resultAdvice = 'Kemungkinan berkembang diabetes sekitar 4-17%. Disarankan melakukan cek gula darah puasa (GDP) dan HbA1c secara rutin berkala.';
            } else {
                this.resultLabel = 'Risiko Diabetes Tinggi';
                this.resultColor = '#EF4444';
                this.resultAdvice = 'Indikator menunjukkan risiko tinggi (1 banding 3). Sangat disarankan berkonsultasi dengan Dokter Spesialis Penyakit Dalam untuk pemeriksaan lab menyeluruh.';
            }
        }
    }
}
</script>
@endpush
@endsection
