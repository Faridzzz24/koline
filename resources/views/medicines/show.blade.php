@extends('layouts.guest')
@section('title', $medicine->name)
@section('content')
<div class="page-wrapper">
    <div class="container">
        <a href="{{ route('medicines.index') }}" class="btn btn-ghost btn-sm mb-6">← Kembali ke Apotek Digital</a>

        <div style="display: grid; grid-template-columns: 1fr 1.25fr; gap: 3.5rem; align-items: start;">
            {{-- Product Header Visual Card --}}
            <div class="card" style="display: flex; align-items: center; justify-content: center; min-height: 340px; background: var(--bg-surface); text-align: center;">
                <div>
                    <span class="badge badge-teal mb-4" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
                        {{ $medicine->category_label }}
                    </span>
                    <h2 style="font-size: 2.25rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.5rem; line-height: 1.3;">{{ $medicine->name }}</h2>
                    <div style="font-size: 1rem; color: var(--txt-muted);">Brand Resmi: {{ $medicine->brand }}</div>
                </div>
            </div>

            {{-- Product Info & Purchase Form --}}
            <div>
                <div class="flex gap-2 mb-4" style="flex-wrap: wrap;">
                    <span class="badge badge-muted">{{ $medicine->category_label }}</span>
                    @if($medicine->requires_prescription)
                        <span class="badge badge-danger">Memerlukan Resep Dokter</span>
                    @else
                        <span class="badge badge-success">Obat Bebas / Non-Resep</span>
                    @endif
                </div>

                <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.5rem; line-height: 1.3;">{{ $medicine->name }}</h1>
                <div style="color: var(--txt-muted); font-size: 1rem; margin-bottom: 1.5rem;">Produsen / Brand: <strong style="color: var(--txt-heading);">{{ $medicine->brand }}</strong></div>

                <div style="font-size: 2.5rem; font-weight: 900; color: var(--clr-teal-light); margin-bottom: 1.75rem;">{{ $medicine->formatted_price }}</div>

                <div class="card card-sm mb-6">
                    <div style="font-weight: 700; color: var(--txt-heading); margin-bottom: 0.5rem;">Deskripsi & Indikasi Produk</div>
                    <p style="color: var(--txt-body); line-height: 1.8; font-size: 0.95rem;">{{ $medicine->description }}</p>
                </div>

                <div style="font-size: 0.9rem; color: var(--txt-muted); margin-bottom: 1.75rem;">
                    Stok Tersedia Saat Ini: <strong style="color: var(--txt-heading);">{{ $medicine->stock }} pcs</strong>
                </div>

                @if(!$medicine->requires_prescription)
                    @auth
                        <form action="{{ route('cart.add', $medicine) }}" method="POST" style="display: flex; gap: 1.25rem; align-items: center;">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $medicine->stock }}" class="form-input" style="width: 110px; text-align: center; height: 48px;">
                            <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; height: 48px;">Tambahkan ke Keranjang Belanja</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block">Masuk Akun untuk Membeli</a>
                    @endauth
                @else
                    <div class="alert alert-warning mb-6">
                        Produk ini tergolong <strong>Obat Keras / Resep Dokter</strong> dan memerlukan persetujuan medis. Silakan berkonsultasi dengan dokter terlebih dahulu.
                    </div>
                    <a href="{{ route('doctors.index') }}" class="btn btn-primary btn-block btn-lg">Konsultasi Dokter Terlebih Dahulu</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
