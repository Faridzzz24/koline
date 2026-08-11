@extends('layouts.app')
@section('title', 'Edit Produk Apotek')
@section('content')
<div class="main-header">
    <div>
        <a href="{{ route('admin.apotek.index') }}" class="btn btn-ghost btn-sm mb-2">← Kembali</a>
        <div class="page-title">💊 Edit Produk Apotek</div>
    </div>
</div>

<div class="card" style="max-width:700px;">
    <form action="{{ route('admin.apotek.update', $apotek) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-2 mb-4">
            <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $apotek->name) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Brand / Merk</label>
                <input type="text" name="brand" value="{{ old('brand', $apotek->brand) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                    <option value="obat_bebas" {{ $apotek->category === 'obat_bebas' ? 'selected' : '' }}>Obat Bebas</option>
                    <option value="obat_keras" {{ $apotek->category === 'obat_keras' ? 'selected' : '' }}>Obat Keras</option>
                    <option value="suplemen" {{ $apotek->category === 'suplemen' ? 'selected' : '' }}>Suplemen</option>
                    <option value="vitamin" {{ $apotek->category === 'vitamin' ? 'selected' : '' }}>Vitamin</option>
                    <option value="herbal" {{ $apotek->category === 'herbal' ? 'selected' : '' }}>Herbal</option>
                    <option value="alat_kesehatan" {{ $apotek->category === 'alat_kesehatan' ? 'selected' : '' }}>Alat Kesehatan</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', (int)$apotek->price) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $apotek->stock) }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Butuh Resep Dokter?</label>
                <select name="requires_prescription" class="form-select">
                    <option value="0" {{ !$apotek->requires_prescription ? 'selected' : '' }}>Tidak</option>
                    <option value="1" {{ $apotek->requires_prescription ? 'selected' : '' }}>Ya (Obat Keras)</option>
                </select>
            </div>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-input" rows="4" required>{{ old('description', $apotek->description) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">💾 Perbarui Produk</button>
    </form>
</div>
@endsection
