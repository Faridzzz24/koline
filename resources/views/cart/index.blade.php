@extends('layouts.guest')
@section('title', 'Keranjang Belanja Apotek')

@section('content')
<div class="page-wrapper" style="padding-top: 135px;">
    <div class="container">
        <div style="max-width: 960px; margin: 0 auto;">

            <div class="flex-between items-center mb-8" style="flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.25rem;">Keranjang Belanja <span class="text-gradient">Apotek</span></h1>
                    <div style="font-size: 0.95rem; color: var(--txt-muted);">{{ count($items) }} produk di keranjang Anda</div>
                </div>
                <a href="{{ route('medicines.index') }}" class="btn btn-outline">+ Tambah Obat Lain</a>
            </div>

            @if(empty($items))
                <div class="card" style="text-align: center; padding: 4.5rem 2rem;">
                    <div style="font-size: 3.5rem; margin-bottom: 1rem;">🛒</div>
                    <h3 style="margin-bottom: 0.5rem; color: var(--txt-heading);">Keranjang Belanja Kosong</h3>
                    <p style="color: var(--txt-muted); max-width: 420px; margin: 0 auto 1.5rem;">Belum ada obat atau suplemen kesehatan yang ditambahkan ke keranjang.</p>
                    <a href="{{ route('medicines.index') }}" class="btn btn-primary" style="display: inline-flex; width: auto;">Belanja di Apotek Digital →</a>
                </div>
            @else
                <div class="grid grid-3" style="gap: 2rem; align-items: start;">

                    {{-- Cart Items List (2 Columns) --}}
                    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 1.25rem;">
                        @foreach($items as $item)
                            <div class="card" style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap;">
                                <div class="flex items-center gap-4" style="flex: 1; min-width: 240px;">
                                    <div style="width: 52px; height: 52px; border-radius: var(--r-md); background: rgba(13, 148, 136, 0.15); color: var(--clr-teal-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--txt-heading); margin-bottom: 0.25rem;">
                                            {{ $item['medicine']->name }}
                                        </h4>
                                        <div style="font-size: 0.825rem; color: var(--txt-muted);">
                                            Brand: <strong style="color: var(--txt-body);">{{ $item['medicine']->brand }}</strong> · Rp {{ number_format($item['medicine']->price, 0, ',', '.') }} / pcs
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-6">
                                    <div style="text-align: right;">
                                        <div style="font-size: 0.775rem; color: var(--txt-muted);">Jumlah: {{ $item['quantity'] }} pcs</div>
                                        <div style="font-size: 1.15rem; font-weight: 800; color: var(--clr-teal-light);">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <form action="{{ route('cart.remove', $item['medicine']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-icon" style="color: var(--clr-danger);" title="Hapus dari Keranjang">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Order Summary Sidebar (1 Column) --}}
                    <div class="card" style="padding: 1.75rem;">
                        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem; color: var(--txt-heading);">Ringkasan Belanja</h3>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--bdr-subtle); padding-bottom: 1rem;">
                            <div class="flex-between" style="font-size: 0.9rem;">
                                <span class="text-muted">Total Subtotal</span>
                                <span style="font-weight: 700; color: var(--txt-heading);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex-between" style="font-size: 0.9rem;">
                                <span class="text-muted">Estimasi Ongkir</span>
                                <span style="font-weight: 700; color: var(--clr-teal-light);">Rp 15.000</span>
                            </div>
                        </div>

                        <div class="flex-between mb-6">
                            <span style="font-weight: 800; font-size: 1rem; color: var(--txt-heading);">Estimasi Total</span>
                            <span style="font-weight: 800; font-size: 1.35rem; color: var(--clr-brand-light);">Rp {{ number_format($total + 15000, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-block btn-lg" style="height: 48px;">
                            Lanjut ke Checkout →
                        </a>
                    </div>

                </div>
            @endif

        </div>
    </div>
</div>
@endsection
