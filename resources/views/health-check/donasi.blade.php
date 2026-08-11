@extends('layouts.guest')
@section('title', 'Donasi Medis KoLine Peduli')

@section('content')
<div class="page-wrapper">
    <div class="container">

        <div style="text-align: center; max-width: 720px; margin: 0 auto 4rem;">
            <a href="{{ route('health-check.index') }}" class="btn btn-ghost btn-sm mb-4">← Kembali ke Pusat Cek Kesehatan</a>
            <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.75rem;">Program <span class="text-gradient">Donasi Medis</span></h1>
            <p style="color: var(--txt-muted); font-size: 1.05rem; line-height: 1.7;">
                Bantu pembiayaan pengobatan, operasi darurat, & pengadaan alat medis bagi pasien tidak mampu.
            </p>
        </div>

        {{-- Donation Campaigns Grid --}}
        <div class="grid grid-3" style="gap: 2rem;" x-data="{ selectedCampaign: null, amount: 50000, paid: false }">

            {{-- Campaign 1 --}}
            <div class="card" style="justify-content: space-between;">
                <div>
                    <span class="badge badge-teal mb-3">Operasi Pediatrik</span>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Bantuan Operasi Jantung Ananda Dikky</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.25rem;">Dikky (4 thn) membutuhkan tindakan operasi penutupan defek sekat jantung segera.</p>
                    <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--r-md); margin-bottom: 1.25rem;">
                        <div class="flex-between mb-2" style="font-size: 0.8rem;">
                            <span class="text-muted">Terkumpul</span>
                            <span style="font-weight: 800; color: var(--clr-brand-light);">Rp 45.500.000</span>
                        </div>
                        <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                            <div style="width: 65%; height: 100%; background: var(--clr-brand);"></div>
                        </div>
                    </div>
                </div>
                <button @click="selectedCampaign = 'Bantuan Operasi Jantung Ananda Dikky'" class="btn btn-primary btn-block">Salurkan Donasi →</button>
            </div>

            {{-- Campaign 2 --}}
            <div class="card" style="justify-content: space-between;">
                <div>
                    <span class="badge badge-primary mb-3">Lansia Dhuafa</span>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Bantuan Operasi Katarak Lansia</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.25rem;">Bantuan gratis operasi katarak & kacamata bagi 50 lansia di pelosok desa.</p>
                    <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--r-md); margin-bottom: 1.25rem;">
                        <div class="flex-between mb-2" style="font-size: 0.8rem;">
                            <span class="text-muted">Terkumpul</span>
                            <span style="font-weight: 800; color: var(--clr-brand-light);">Rp 28.000.000</span>
                        </div>
                        <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                            <div style="width: 80%; height: 100%; background: var(--clr-teal-light);"></div>
                        </div>
                    </div>
                </div>
                <button @click="selectedCampaign = 'Bantuan Operasi Katarak Lansia'" class="btn btn-primary btn-block">Salurkan Donasi →</button>
            </div>

            {{-- Campaign 3 --}}
            <div class="card" style="justify-content: space-between;">
                <div>
                    <span class="badge badge-warning mb-3">Alat Kesehatan</span>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Pengadaan Kursi Roda & Tabung Oksigen</h3>
                    <p style="color: var(--txt-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.25rem;">Penyediaan alat bantu jalan & oksigen medis gratis untuk pasien rawat jalan rumah.</p>
                    <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--r-md); margin-bottom: 1.25rem;">
                        <div class="flex-between mb-2" style="font-size: 0.8rem;">
                            <span class="text-muted">Terkumpul</span>
                            <span style="font-weight: 800; color: var(--clr-brand-light);">Rp 18.200.000</span>
                        </div>
                        <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                            <div style="width: 45%; height: 100%; background: var(--clr-warning);"></div>
                        </div>
                    </div>
                </div>
                <button @click="selectedCampaign = 'Pengadaan Kursi Roda & Tabung Oksigen'" class="btn btn-primary btn-block">Salurkan Donasi →</button>
            </div>

            {{-- Donation Modal Popover --}}
            <div x-show="selectedCampaign" x-transition style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1.5rem;" x-cloak>
                <div class="card" style="width: 100%; max-width: 480px; padding: 2.25rem; background: var(--bg-card);">
                    <div class="flex-between mb-4">
                        <h3 style="font-size: 1.25rem; margin-bottom: 0;" x-text="selectedCampaign"></h3>
                        <button @click="selectedCampaign = null; paid = false;" class="btn btn-ghost btn-sm">✕</button>
                    </div>

                    <div x-show="!paid">
                        <div class="form-group mb-6">
                            <label class="form-label">Pilih Nominal Donasi (Rp)</label>
                            <div class="grid grid-3 gap-2 mb-3">
                                <button type="button" @click="amount = 20000" :class="amount === 20000 ? 'btn-primary' : 'btn-outline'" class="btn btn-sm">20.000</button>
                                <button type="button" @click="amount = 50000" :class="amount === 50000 ? 'btn-primary' : 'btn-outline'" class="btn btn-sm">50.000</button>
                                <button type="button" @click="amount = 100000" :class="amount === 100000 ? 'btn-primary' : 'btn-outline'" class="btn btn-sm">100.000</button>
                            </div>
                            <input type="number" x-model.number="amount" class="form-input" min="10000" step="5000" placeholder="Nominal lain...">
                        </div>

                        <button @click="paid = true" class="btn btn-teal btn-block btn-lg">Konfirmasi Donasi Medis</button>
                    </div>

                    <div x-show="paid" style="text-align: center; padding: 1.5rem 0;">
                        <div style="font-size: 3rem; margin-bottom: 0.5rem;">❤️</div>
                        <h2 style="font-size: 1.5rem; font-weight: 800; color: #34D399; margin-bottom: 0.5rem;">Terima Kasih!</h2>
                        <p style="color: var(--txt-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Donasi medis Anda sebesar <strong style="color: white;" x-text="'Rp ' + amount.toLocaleString('id-ID')"></strong> telah berhasil disalurkan.</p>
                        <button @click="selectedCampaign = null; paid = false;" class="btn btn-primary">Selesai</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
