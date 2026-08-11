@extends('layouts.guest')
@section('title', 'Kalkulator Kalender Menstruasi & Masa Subur')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="menstruasiApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Kalender <span class="text-gradient">Menstruasi & Ovulasi</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Hitung perkiraan tanggal menstruasi berikutnya, puncak masa subur, & hari ovulasi secara presisi.
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                <div x-show="!calculated">
                    <form @submit.prevent="calculateCycle()">
                        <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.75rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Hari Pertama Haid Terakhir (HPHT)</label>
                                <input type="date" x-model="lastDate" class="form-input" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Rata-rata Durasi Siklus (Hari)</label>
                                <input type="number" x-model.number="cycleLength" class="form-input" min="20" max="45" placeholder="Default: 28 hari" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Hitung Perkiraan Siklus Menstruasi
                        </button>
                    </form>
                </div>

                {{-- Result Screen --}}
                <div x-show="calculated" x-transition>
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div class="badge badge-teal mb-3">Jadwal Siklus Reproduksi</div>
                        <h2 style="font-size: 1.75rem; font-weight: 800;">Perkiraan Siklus Berikutnya</h2>
                    </div>

                    <div class="grid grid-3" style="gap: 1.25rem; margin-bottom: 2rem;">
                        <div style="background: var(--bg-surface); padding: 1.25rem; border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle); text-align: center;">
                            <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Haid Berikutnya</div>
                            <div style="font-weight: 800; color: #EC4899; font-size: 1.1rem;" x-text="nextPeriodStr"></div>
                        </div>
                        <div style="background: var(--bg-surface); padding: 1.25rem; border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle); text-align: center;">
                            <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Hari Ovulasi</div>
                            <div style="font-weight: 800; color: #2DD4BF; font-size: 1.1rem;" x-text="ovulationStr"></div>
                        </div>
                        <div style="background: var(--bg-surface); padding: 1.25rem; border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle); text-align: center;">
                            <div style="font-size: 0.775rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Puncak Masa Subur</div>
                            <div style="font-weight: 800; color: #38BDF8; font-size: 1rem;" x-text="fertileStr"></div>
                        </div>
                    </div>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.25rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle); font-size: 0.9rem; color: var(--txt-body); line-height: 1.6;">
                        Catatan: Hasil ini merupakan perkiraan berdasarkan algoritma matematis siklus teratur. Jika Anda mengalami keterlambatan haid berlebih atau siklus tidak teratur, disarankan konsultasi dengan Dokter Spesialis Obgyn KoLine.
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="calculated = false" class="btn btn-outline">Hitung Ulang</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Dokter Obgyn →</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function menstruasiApp() {
    return {
        lastDate: new Date().toISOString().split('T')[0],
        cycleLength: 28,
        calculated: false,
        nextPeriodStr: '', ovulationStr: '', fertileStr: '',

        calculateCycle() {
            if (!this.lastDate) return;
            const start = new Date(this.lastDate);

            // Next period = start + cycleLength days
            const nextPeriod = new Date(start);
            nextPeriod.setDate(nextPeriod.getDate() + this.cycleLength);

            // Ovulation = nextPeriod - 14 days
            const ovulation = new Date(nextPeriod);
            ovulation.setDate(ovulation.getDate() - 14);

            // Fertile window = ovulation - 3 days to ovulation + 1 day
            const fertileStart = new Date(ovulation);
            fertileStart.setDate(fertileStart.getDate() - 3);
            const fertileEnd = new Date(ovulation);
            fertileEnd.setDate(fertileEnd.getDate() + 1);

            const opt = { day: 'numeric', month: 'short', year: 'numeric' };
            this.nextPeriodStr = nextPeriod.toLocaleDateString('id-ID', opt);
            this.ovulationStr = ovulation.toLocaleDateString('id-ID', opt);
            this.fertileStr = `${fertileStart.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })} - ${fertileEnd.toLocaleDateString('id-ID', opt)}`;

            this.calculated = true;
        }
    }
}
</script>
@endpush
@endsection
