@extends('layouts.app')
@section('title', 'Tambah Produk Apotek')
@section('content')
<div class="main-header">
    <div>
        <a href="{{ route('admin.apotek.index') }}" class="btn btn-ghost btn-sm mb-2">← Kembali</a>
        <div class="page-title">💊 Tambah Produk Apotek</div>
    </div>
</div>

<div class="card" style="max-width:700px;">
    <form action="{{ route('admin.apotek.store') }}" method="POST">
        @csrf
        <div class="grid grid-2 mb-4">
            <div class="form-group">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-input" placeholder="Paracetamol 500mg" required>
            </div>
            <div class="form-group">
                <label class="form-label">Brand / Merk</label>
                <input type="text" name="brand" class="form-input" placeholder="Kalbe" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                    <option value="obat_bebas">Obat Bebas</option>
                    <option value="obat_keras">Obat Keras</option>
                    <option value="suplemen">Suplemen</option>
                    <option value="vitamin">Vitamin</option>
                    <option value="herbal">Herbal</option>
                    <option value="alat_kesehatan">Alat Kesehatan</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" class="form-input" value="10000" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" class="form-input" value="100" required>
            </div>
            <div class="form-group">
                <label class="form-label">Butuh Resep Dokter?</label>
                <select name="requires_prescription" class="form-select">
                    <option value="0">Tidak</option>
                    <option value="1">Ya (Obat Keras)</option>
                </select>
            </div>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-input" rows="4" placeholder="Manfaat & penggunaan..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">🚀 Simpan Produk</button>
    </form>
</div>
@endsection
