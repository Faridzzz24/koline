@extends('layouts.guest')
@section('title', 'Riwayat Pesanan Apotek Digital')

@section('content')
<div class="page-wrapper" style="padding-top: 135px;">
    <div class="container">
        <div style="max-width: 960px; margin: 0 auto;">

            <div class="flex-between items-center mb-8" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.25rem;">Riwayat Pesanan <span class="text-gradient">Apotek</span></h1>
                    <div style="font-size: 0.95rem; color: var(--txt-muted);">Daftar transaksi pembelian obat-obatan & suplemen kesehatan Anda</div>
                </div>
                <a href="{{ route('medicines.index') }}" class="btn btn-primary">+ Belanja Obat Baru</a>
            </div>

            @if($orders->isEmpty())
                <div class="card" style="text-align: center; padding: 4.5rem 2rem;">
                    <div style="font-size: 3.5rem; margin-bottom: 1rem;">📦</div>
                    <h3 style="margin-bottom: 0.5rem; color: var(--txt-heading);">Belum Ada Pesanan</h3>
                    <p style="color: var(--txt-muted); max-width: 420px; margin: 0 auto 1.5rem;">Anda belum memiliki riwayat transaksi pemesanan obat di Apotek Digital KoLine.</p>
                    <a href="{{ route('medicines.index') }}" class="btn btn-primary" style="display: inline-flex; width: auto;">Mulai Belanja di Apotek →</a>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @foreach($orders as $order)
                        <div class="card" style="padding: 1.75rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--bdr-subtle); margin-bottom: 1.25rem;">
                                <div>
                                    <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.05rem;">No. Pesanan: #{{ $order->order_number }}</div>
                                    <div style="font-size: 0.825rem; color: var(--txt-muted); margin-top: 0.25rem;">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="badge badge-{{ match($order->status) { 'completed' => 'success', 'paid' => 'teal', 'cancelled' => 'danger', default => 'warning' } }}">
                                        Status: {{ match($order->status) { 'pending' => 'Menunggu Pembayaran', 'paid' => 'Sudah Dibayar', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($order->status) } }}
                                    </span>
                                    <div style="font-size: 1.2rem; font-weight: 800; color: var(--clr-teal-light);">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            {{-- Order Items --}}
                            <div style="display: flex; flex-direction: column; gap: 0.625rem; margin-bottom: 1.25rem;">
                                @foreach($order->items as $item)
                                    <div class="flex-between" style="font-size: 0.9rem;">
                                        <span style="color: var(--txt-body);">• {{ $item->medicine ? $item->medicine->name : 'Produk Farmasi' }} x{{ $item->quantity }}</span>
                                        <span style="font-weight: 600; color: var(--txt-heading);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div style="padding-top: 1.25rem; border-top: 1px solid var(--bdr-subtle); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                                <div style="font-size: 0.85rem; color: var(--txt-muted);">Tujuan: {{ Str::limit($order->shipping_address, 55) }}</div>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm">Detail Pesanan →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($orders->hasPages())
                    <div style="margin-top: 3rem; display: flex; justify-content: center;">
                        {{ $orders->links('vendor.pagination.custom') }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
@endsection
