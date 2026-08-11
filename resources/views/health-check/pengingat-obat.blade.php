@extends('layouts.guest')
@section('title', 'Pengingat Obat Digital')

@section('content')
<div class="page-wrapper">
    <div class="container">
        <div style="max-width: 760px; margin: 0 auto;" x-data="pengingatApp()">

            <div style="text-align: center; margin-bottom: 3rem;">
                <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
                <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Pengingat <span class="text-gradient">Obat Digital</span></h1>
                <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                    Kelola jadwal konsumsi obat harian & pantau kedisiplinan minum obat sesuai petunjuk resep medis.
                </p>
            </div>

            <div class="card mb-6" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.25rem;">+ Tambah Jadwal Obat Baru</h3>
                <form @submit.prevent="addReminder()" class="grid grid-3" style="gap: 1rem; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nama Obat / Suplemen</label>
                        <input type="text" x-model="name" placeholder="Contoh: Paracetamol 500mg" class="form-input" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Dosis / Frekuensi</label>
                        <select x-model="dosage" class="form-select">
                            <option value="1x Sehari">1x Sehari</option>
                            <option value="2x Sehari">2x Sehari</option>
                            <option value="3x Sehari">3x Sehari</option>
                            <option value="Sesuai Kebutuhan">Sesuai Kebutuhan</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Jam Minum</label>
                        <input type="time" x-model="time" class="form-input" required>
                    </div>
                    <div style="grid-column: span 3; margin-top: 0.5rem;">
                        <button type="submit" class="btn btn-primary btn-block" style="height: 48px;">Simpan Pengingat Obat</button>
                    </div>
                </form>
            </div>

            {{-- Reminders List --}}
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.25rem;">Daftar Jadwal Obat Aktif</h3>

                <template x-if="reminders.length === 0">
                    <div style="text-align: center; color: var(--txt-muted); padding: 3rem 0;">
                        Belum ada pengingat obat yang tersimpan. Tambahkan jadwal obat Anda di atas.
                    </div>
                </template>

                <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                    <template x-for="(item, idx) in reminders" :key="idx">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.125rem 1.5rem; background: var(--bg-surface); border-radius: var(--r-md); border: 1px solid var(--bdr-subtle);">
                            <div class="flex items-center gap-4">
                                <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); color: #34D399; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                                    <span x-text="item.time"></span>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--txt-heading); font-size: 1.05rem;" x-text="item.name"></div>
                                    <div style="font-size: 0.825rem; color: var(--txt-muted);" x-text="item.dosage"></div>
                                </div>
                            </div>
                            <button @click="removeReminder(idx)" class="btn btn-ghost btn-sm" style="color: var(--clr-danger);" title="Hapus">Hapus</button>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
function pengingatApp() {
    return {
        name: '', dosage: '3x Sehari', time: '08:00',
        reminders: [
            { name: 'Vitamin C 500mg', dosage: '1x Sehari', time: '08:00' },
            { name: 'Paracetamol 500mg', dosage: '3x Sehari', time: '13:00' }
        ],

        addReminder() {
            if (!this.name || !this.time) return;
            this.reminders.push({
                name: this.name,
                dosage: this.dosage,
                time: this.time
            });
            this.name = '';
        },
        removeReminder(idx) {
            this.reminders.splice(idx, 1);
        }
    }
}
</script>
@endpush
@endsection
