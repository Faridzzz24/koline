@extends('layouts.guest')
@section('title', 'Skrining Risiko Jantung')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 720px; margin: 0 auto;" x-data="jantungApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Skrining <span class="text-gradient">Risiko Jantung</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Kalkulator rujukan indikator kardiovaskular berdasarkan pola hidup, riwayat hipertensi, dan usia.
                </p>
            </div>

            <div class="card" style="padding: 2.5rem;">
                <div x-show="!submitted">
                    <form @submit.prevent="calculateRisk()">
                        <div style="display: flex; flex-direction: column; gap: 1.75rem; margin-bottom: 2rem;">
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Usia Anda (Tahun)</label>
                                <input type="number" x-model.number="age" class="form-input" placeholder="Contoh: 45" required min="18" max="100">
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Riwayat Merokok</label>
                                <div style="display: flex; gap: 0.5rem; background: var(--bg-input); padding: 0.35rem; border-radius: var(--r-md); border: 1px solid var(--bdr-subtle); height: 48px; align-items: center;">
                                    <button type="button" @click="smoking = 'no'" :class="smoking === 'no' ? 'btn-primary' : 'btn-ghost'" class="btn btn-sm" style="flex: 1; height: 38px;">
                                        Tidak Merokok
                                    </button>
                                    <button type="button" @click="smoking = 'yes'" :class="smoking === 'yes' ? 'btn-primary' : 'btn-ghost'" class="btn btn-sm" style="flex: 1; height: 38px;">
                                        Merokok Aktif
                                    </button>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Tekanan Darah Sistolik</label>
                                <div class="grid grid-3" style="gap: 0.5rem;">
                                    <button type="button" @click="bp = 'normal'" :class="bp === 'normal' ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px;">
                                        Normal (&lt;120)
                                    </button>
                                    <button type="button" @click="bp = 'elevated'" :class="bp === 'elevated' ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px;">
                                        Pre-Hipertensi
                                    </button>
                                    <button type="button" @click="bp = 'high'" :class="bp === 'high' ? 'btn-primary' : 'btn-outline'" class="btn btn-sm" style="height: 44px;">
                                        Hipertensi (≥140)
                                    </button>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Aktivitas Fisik / Olahraga</label>
                                <div style="display: flex; gap: 0.5rem; background: var(--bg-input); padding: 0.35rem; border-radius: var(--r-md); border: 1px solid var(--bdr-subtle); height: 48px; align-items: center;">
                                    <button type="button" @click="activity = 'active'" :class="activity === 'active' ? 'btn-primary' : 'btn-ghost'" class="btn btn-sm" style="flex: 1; height: 38px;">
                                        Rutin (&gt;150 mnt/minggu)
                                    </button>
                                    <button type="button" @click="activity = 'rare'" :class="activity === 'rare' ? 'btn-primary' : 'btn-ghost'" class="btn btn-sm" style="flex: 1; height: 38px;">
                                        Jarang / Sedentari
                                    </button>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 50px;">
                            Hitung Risiko Kardiovaskular
                        </button>
                    </form>
                </div>

                {{-- Result --}}
                <div x-show="submitted" x-transition style="text-align: center;">
                    <div class="badge badge-teal mb-4" style="font-size: 0.85rem; padding: 0.4rem 1rem;">Hasil Indikator Kardiovaskular</div>

                    <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 1rem;" :style="`color: ${resultColor}`" x-text="resultLabel"></h2>

                    <div style="background: var(--bg-surface); border-radius: var(--r-lg); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--bdr-subtle); text-align: left;">
                        <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Saran Tindakan Medis Kardio:</div>
                        <p style="font-size: 0.925rem; color: var(--txt-body); line-height: 1.7; margin-bottom: 0;" x-text="resultAdvice"></p>
                    </div>

                    <div class="flex gap-4 justify-center flex-wrap">
                        <button @click="submitted = false" class="btn btn-outline">Ulangi Hitung</button>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary">Konsultasi Dokter Jantung →</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function jantungApp() {
    return {
        age: 35, smoking: 'no', bp: 'normal', activity: 'active',
        submitted: false,
        resultLabel: '', resultColor: '', resultAdvice: '',

        calculateRisk() {
            let score = 0;
            if (this.age > 45) score += 2;
            if (this.smoking === 'yes') score += 3;
            if (this.bp === 'high') score += 3;
            if (this.bp === 'elevated') score += 1;
            if (this.activity === 'rare') score += 1;

            this.submitted = true;
            if (score <= 2) {
                this.resultLabel = 'Risiko Jantung Rendah';
                this.resultColor = '#10B981';
                this.resultAdvice = 'Indikator kesehatan jantung Anda berada dalam kategori baik. Pertahankan pola makan rendah garam, hindari rokok, dan lakukan aktivitas kardio rutin.';
            } else if (score <= 4) {
                this.resultLabel = 'Risiko Jantung Sedang';
                this.resultColor = '#F59E0B';
                this.resultAdvice = 'Terdapat beberapa faktor risiko yang perlu diwaspadai. Disarankan melakukan cek profil lipid (kolesterol) berkala dan konsultasi dengan dokter umum/spesialis.';
            } else {
                this.resultLabel = 'Risiko Jantung Tinggi';
                this.resultColor = '#EF4444';
                this.resultAdvice = 'Indikator menunjukkan potensi risiko kardiovaskular tinggi. Sangat disarankan untuk segera berkonsultasi dengan Dokter Spesialis Jantung di KoLine.';
            }
        }
    }
}
</script>
@endpush
@endsection
