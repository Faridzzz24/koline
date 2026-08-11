@extends('layouts.guest')
@section('title', 'Kalkulator Kehamilan & HPL')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="kehamilanApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Kalkulator <span class="text-gradient">Kehamilan & HPL</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Hitung Hari Perkiraan Lahir (HPL) & usia kehamilan saat ini berdasarkan Aturan Naegele.
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                <div x-show="!calculated">
                    <form @submit.prevent="calculatePregnancy()">
                        <div class="form-group mb-6">
                            <label class="form-label">Hari Pertama Haid Terakhir (HPHT)</label>
                            <input type="date" x-model="hpht" class="form-input" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Hitung Perkiraan Hari Kelahiran (HPL)
                        </button>
                    </form>
                </div>

                {{-- Result Screen --}}
                <div x-show="calculated" x-transition>
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div class="badge badge-teal mb-3">Estimasi Hari Kelahiran</div>
                        <div style="font-size: 2.75rem; font-weight: 900; color: #818CF8; margin-bottom: 0.25rem;" x-text="hplStr"></div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--txt-heading);" x-text="'Usia Kehamilan: ' + weeks + ' Minggu (' + trimester + ')'"></h3>
                    </div>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle);">
                        <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Panduan Nutrisi & Pemeriksaan Trimester:</div>
                        <p style="font-size: 0.925rem; color: var(--txt-body); line-height: 1.7; margin-bottom: 0;" x-text="advice"></p>
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="calculated = false" class="btn btn-outline">Hitung Ulang</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Dokter Kebidanan →</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function kehamilanApp() {
    return {
        hpht: new Date().toISOString().split('T')[0],
        calculated: false,
        hplStr: '', weeks: 0, trimester: '', advice: '',

        calculatePregnancy() {
            if (!this.hpht) return;
            const start = new Date(this.hpht);

            // Naegele's Rule: HPL = HPHT + 280 days (40 weeks)
            const hpl = new Date(start);
            hpl.setDate(hpl.getDate() + 280);

            // Current Gestational Age = (Today - HPHT) in weeks
            const today = new Date();
            const diffDays = Math.floor((today - start) / (1000 * 60 * 60 * 24));
            this.weeks = Math.max(1, Math.floor(diffDays / 7));

            this.hplStr = hpl.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

            if (this.weeks <= 12) {
                this.trimester = 'Trimester 1';
                this.advice = 'Fokus pada konsumsi asam folat, hindari kelelahan berlebih, dan jadwalkan pemeriksaan USG pertama dengan Dokter Kebidanan.';
            } else if (this.weeks <= 27) {
                this.trimester = 'Trimester 2';
                this.advice = 'Pertumbuhan janin meningkat pesat. Pastikan asupan zat besi & kalsium tercukupi, serta pantau gerakan janin teratur.';
            } else {
                this.trimester = 'Trimester 3';
                this.advice = 'Persiapkan perlengkapan persalinan & rumah sakit rujukan. Lakukan cek kehamilan lebih sering menjelang hari perkiraan lahir.';
            }

            this.calculated = true;
        }
    }
}
</script>
@endpush
@endsection
