@extends('layouts.guest')
@section('title', 'Checkout Pesanan Apotek')

@section('content')
<div class="page-wrapper" style="padding-top: 130px;">
    <div class="container">
        <div style="max-width: 960px; margin: 0 auto;">

            <div style="margin-bottom: 2.5rem;">
                <a href="{{ route('cart.index') }}" class="btn btn-ghost btn-sm mb-3">← Kembali ke Keranjang</a>
                <h1 style="font-size: 2rem; font-weight: 800;">Checkout <span class="text-gradient">Pesanan Obat</span></h1>
                <p style="color: var(--txt-muted); font-size: 0.95rem;">Lengkapi informasi pengiriman & konfirmasi pemesanan produk farmasi Anda.</p>
            </div>

            <form action="{{ route('cart.order') }}" method="POST" class="grid grid-3" style="gap: 2rem; align-items: start;">
                @csrf

                {{-- Left: Shipping Details (2 Columns) --}}
                <div style="grid-column: span 2;" class="card">
                    <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 0.875rem;">Informasi Penerima & Pengiriman</h3>

                    <div class="form-group mb-4">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" class="form-input" placeholder="Nama Lengkap Penerima" required>
                        @error('shipping_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Nomor Telepon / WhatsApp</label>
                        <input type="tel" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" class="form-input" placeholder="081234567890" required>
                        @error('shipping_phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Alamat Lengkap Pengiriman</label>
                        <textarea name="shipping_address" class="form-input" rows="3" placeholder="Alamat rumah, nomor rumah, RT/RW, Kecamatan, Kota..." required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        @error('shipping_address')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Catatan Tambahan untuk Kurir / Apoteker (Opsional)</label>
                        <input type="text" name="notes" class="form-input" placeholder="Contoh: Titipkan di pos satpam...">
                    </div>
                </div>

                {{-- Right: Order Summary Sidebar (1 Column) --}}
                <div class="card" style="padding: 1.75rem;">
                    <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Rincian Pembayaran</h3>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 1rem;">
                        <div class="flex-between" style="font-size: 0.9rem;">
                            <span class="text-muted">Subtotal Produk</span>
                            <span style="font-weight: 700; color: var(--txt-heading);">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex-between" style="font-size: 0.9rem;">
                            <span class="text-muted">Ongkos Kirim (Kurir Instan)</span>
                            <span style="font-weight: 700; color: var(--txt-heading);">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex-between mb-6">
                        <span style="font-weight: 800; font-size: 1rem; color: var(--txt-heading);">Total Pembayaran</span>
                        <span style="font-weight: 800; font-size: 1.4rem; color: var(--clr-brand-light);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div style="background: var(--bg-surface); padding: 0.875rem; border-radius: var(--r-md); border: 1px solid var(--bdr-subtle); margin-bottom: 1.5rem; font-size: 0.8rem; color: var(--txt-muted); line-height: 1.5;">
                        💳 Metode Pembayaran: <strong style="color: var(--clr-teal-light);">COD / Transfer Bank Simulasi KoLine</strong>.
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="height: 48px;">
                        Konfirmasi & Buat Pesanan →
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
