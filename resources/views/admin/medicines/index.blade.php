@extends('layouts.app')
@section('title', 'Manajemen Apotek | KoLine')

@section('content')
<div class="main-header mb-6 flex-between items-center">
    <div>
        <h1 style="font-size: 1.65rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">💊 Manajemen Apotek & Obat</h1>
        <div style="font-size: 0.875rem; color: var(--txt-muted);">Kelola katalog obat-obatan, persediaan stok, dan status resep dokter</div>
    </div>
    <a href="{{ route('admin.apotek.create') }}" class="btn btn-primary" style="font-weight: 700;">+ Tambah Produk Baru</a>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 1.5rem;">Produk Obat</th>
                <th style="text-align: center;">Kategori</th>
                <th style="text-align: center;">Harga</th>
                <th style="text-align: center;">Stok</th>
                <th style="text-align: center;">Resep</th>
                <th style="text-align: right; padding-right: 1.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medicines as $m)
                <tr>
                    <td style="text-align: left; padding-left: 1.5rem;">
                        <div style="font-weight: 700; color: var(--txt-heading); font-size: 0.925rem;">{{ $m->name }}</div>
                        <div style="font-size: 0.775rem; color: var(--txt-muted); font-weight: 500;">{{ $m->brand }}</div>
                    </td>
                    <td style="text-align: center;">
                        <span style="color: var(--txt-heading); font-weight: 500; font-size: 0.875rem;">{{ $m->category_label }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span style="color: var(--clr-brand-light); font-weight: 700; font-size: 0.9rem;">{{ $m->formatted_price }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span style="color: var(--txt-heading); font-weight: 600; font-size: 0.875rem;">{{ $m->stock }}</span>
                    </td>
                    <td style="text-align: center;">
                        @if($m->requires_prescription)
                            <span style="color: #F87171; font-weight: 700; font-size: 0.85rem;">Ya</span>
                        @else
                            <span style="color: #34D399; font-weight: 500; font-size: 0.85rem;">Tidak</span>
                        @endif
                    </td>
                    <td style="text-align: right; padding-right: 1.5rem;">
                        <div class="flex items-center gap-2" style="justify-content: flex-end;">
                            <a href="{{ route('admin.apotek.edit', $m) }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.85rem; font-size: 0.8rem;">Edit</a>
                            <form action="{{ route('admin.apotek.destroy', $m) }}" method="POST" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus produk obat ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost btn-sm" style="color: var(--clr-danger); padding: 0.35rem 0.85rem; font-size: 0.8rem;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($medicines->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $medicines->links('vendor.pagination.custom') }}
    </div>
@endif
@endsection
