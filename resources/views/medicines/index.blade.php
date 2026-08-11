@extends('layouts.guest')
@section('title', 'Apotek Digital & Produk Farmasi')

@section('content')
<div class="page-wrapper" style="padding-top: 140px;">
    <div class="container">

        {{-- Page Header --}}
        <div style="margin-bottom: 3.5rem;">
            <div class="badge badge-teal mb-3" style="padding: 0.4rem 1rem;">Apotek Resmi BPOM</div>
            <h1 style="font-size: clamp(2.25rem, 3.75vw, 3rem); font-weight: 800; margin-bottom: 0.75rem; line-height: 1.25;">
                Apotek Digital <span class="text-gradient">KoLine</span>
            </h1>
            <p style="color: var(--txt-muted); font-size: 1.1rem; max-width: 680px; line-height: 1.75;">
                Pengadaan obat-obatan resmi, suplemen kesehatan, dan peralatan medis terverifikasi terjamin keasliannya.
            </p>
        </div>

        {{-- Filter Bar & Category Pill Chips --}}
        <div style="margin-bottom: 2.5rem;">
            <form method="GET" action="{{ route('medicines.index') }}" style="margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="position: relative; flex: 1;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama obat, suplemen, atau merek (contoh: Paracetamol, Vitamin C)..." class="form-input" style="height: 50px; padding-left: 2.75rem;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--txt-muted);">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <button type="submit" class="btn btn-primary" style="height: 50px; padding: 0 2rem;">Cari</button>
                </div>
            </form>

            {{-- Category Pill Chips Bar --}}
            <div class="flex items-center gap-2 flex-wrap" style="padding-bottom: 0.5rem;">
                <a href="{{ route('medicines.index', array_merge(request()->except('category'), ['category' => ''])) }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline' }}" style="border-radius: var(--r-full); padding: 0.5rem 1.25rem;">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('medicines.index', array_merge(request()->except('category'), ['category' => $cat])) }}" class="btn btn-sm {{ request('category') === $cat ? 'btn-primary' : 'btn-outline' }}" style="border-radius: var(--r-full); padding: 0.5rem 1.25rem;">
                        {{ match($cat) {
                            'obat_bebas' => 'Obat Bebas', 'obat_keras' => 'Obat Keras',
                            'suplemen' => 'Suplemen', 'vitamin' => 'Vitamin',
                            'herbal' => 'Herbal', 'alat_kesehatan' => 'Alat Kesehatan',
                            default => ucfirst($cat)
                        } }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Product Catalog Grid --}}
        @if($medicines->isEmpty())
            <div class="card" style="text-align: center; padding: 5rem 2rem;">
                <h3 style="color: var(--txt-heading); margin-bottom: 0.75rem;">Produk Tidak Ditemukan</h3>
                <p style="color: var(--txt-muted); max-width: 480px; margin: 0 auto 1.5rem;">Coba gunakan kata kunci pencarian lain atau tampilkan seluruh kategori.</p>
                <a href="{{ route('medicines.index') }}" class="btn btn-outline" style="display: inline-flex; width: auto;">Tampilkan Semua Obat</a>
            </div>
        @else
            <div class="grid grid-4" style="gap: 2rem;">
                @foreach($medicines as $medicine)
                    <div class="med-card">
                        <div>
                            {{-- Top Single Badge & Icon --}}
                            <div class="flex-between items-center mb-4">
                                <div class="med-icon-wrapper">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </div>
                                @if($medicine->requires_prescription)
                                    <span class="badge badge-danger">Resep Dokter</span>
                                @else
                                    <span class="badge badge-teal">Bebas</span>
                                @endif
                            </div>

                            {{-- Product Title & Brand --}}
                            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.375rem; line-height: 1.35; color: var(--txt-heading);">
                                {{ $medicine->name }}
                            </h3>
                            <div style="font-size: 0.85rem; color: var(--txt-muted); margin-bottom: 1.25rem;">
                                Brand: <strong style="color: var(--txt-body);">{{ $medicine->brand }}</strong>
                            </div>

                            {{-- Price & Stock --}}
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--clr-teal-light); margin-bottom: 0.25rem;">
                                {{ $medicine->formatted_price }}
                            </div>
                            <div style="font-size: 0.775rem; color: var(--txt-muted);">
                                Stok Tersedia: {{ $medicine->stock }} pcs
                            </div>
                        </div>

                        {{-- Actions Bar --}}
                        <div class="med-card-actions">
                            <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-outline" style="flex: 1;">Detail</a>
                            @if(!$medicine->requires_prescription)
                                @auth
                                    <form action="{{ route('cart.add', $medicine) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">+ Beli</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary" style="flex: 1;">Beli</a>
                                @endauth
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($medicines->hasPages())
                <div style="margin-top: 3.5rem; display: flex; justify-content: center;">
                    {{ $medicines->withQueryString()->links('vendor.pagination.custom') }}
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
