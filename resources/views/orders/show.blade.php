@extends('layouts.guest')
@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="page-wrapper" style="padding-top: 135px;">
    <div class="container">
        <div style="max-width: 960px; margin: 0 auto;">

            {{-- Header Bar --}}
            <div class="flex-between items-center mb-8" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <a href="{{ route('orders.index') }}" class="btn btn-ghost btn-sm mb-3">← Kembali ke Daftar Pesanan</a>
                    <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 0.25rem;">Detail <span class="text-gradient">Pesanan Obat</span></h1>
                    <div style="font-size: 0.9rem; color: var(--txt-muted);">Nomor Referensi: <strong style="color: var(--txt-heading);">#{{ $order->order_number }}</strong></div>
                </div>
                <div>
                    <span class="badge badge-{{ match($order->status) { 'delivered' => 'success', 'confirmed', 'processing', 'shipped' => 'teal', 'cancelled' => 'danger', default => 'warning' } }}" style="font-size: 0.85rem; padding: 0.5rem 1.25rem;">
                        Status: {{ match($order->status) { 'pending' => 'Menunggu Pembayaran', 'confirmed' => 'Sudah Dibayar (Dikonfirmasi)', 'processing' => 'Diproses Apotek', 'shipped' => 'Dalam Pengiriman', 'delivered' => 'Pesanan Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($order->status) } }}
                    </span>
                </div>
            </div>

            <div class="grid grid-3" style="gap: 2rem; align-items: start;">
                {{-- Left: Products List (2 Columns) --}}
                <div style="grid-column: span 2;" class="card">
                    <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem; color: var(--txt-heading); border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 0.875rem;">Rincian Produk Farmasi</h3>

                    <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                        @foreach($order->items as $item)
                            <div class="flex-between items-center" style="padding-bottom: 1rem; border-bottom: 1px solid var(--bdr-subtle);">
                                <div>
                                    <div style="font-weight: 700; color: var(--txt-heading); font-size: 1.05rem;">
                                        {{ $item->medicine ? $item->medicine->name : 'Produk Farmasi' }}
                                    </div>
                                    <div style="font-size: 0.825rem; color: var(--txt-muted); margin-top: 0.25rem;">
                                        Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }} pcs
                                    </div>
                                </div>
                                <div style="font-weight: 800; color: var(--clr-teal-light); font-size: 1.1rem;">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex-between items-center">
                        <span style="font-weight: 800; font-size: 1rem; color: var(--txt-heading);">Total Pembayaran</span>
                        <span style="font-weight: 900; font-size: 1.4rem; color: var(--clr-brand-light);">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Right: Shipping & Action Sidebar (1 Column) --}}
                <div>
                    <div class="card mb-6" style="padding: 1.75rem;">
                        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Informasi Pengiriman</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem;">
                            <div>
                                <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Nama Penerima</div>
                                <div style="font-weight: 700; color: var(--txt-heading);">{{ $order->shipping_name }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Nomor Telepon</div>
                                <div style="font-weight: 700; color: var(--txt-heading);">{{ $order->shipping_phone }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Alamat Tujuan</div>
                                <div style="color: var(--txt-body); line-height: 1.6;">{{ $order->shipping_address }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; margin-bottom: 0.25rem;">Metode Pembayaran</div>
                                <div style="font-weight: 600; color: var(--clr-teal-light);">COD / Transfer Bank KoLine</div>
                            </div>
                        </div>
                    </div>

                    @if($order->status === 'pending')
                        <form action="{{ route('orders.pay', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-teal btn-block btn-lg" style="height: 50px;">
                                Simulasi Bayar Pesanan →
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
