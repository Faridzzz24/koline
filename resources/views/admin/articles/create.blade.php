@extends('layouts.app')
@section('title', 'Tambah Artikel')
@section('content')
<div class="main-header">
    <div>
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-ghost btn-sm mb-2">← Kembali</a>
        <div class="page-title">📰 Tulis Artikel Baru</div>
    </div>
</div>

<div class="card" style="max-width:800px;">
    <form action="{{ route('admin.artikel.store') }}" method="POST">
        @csrf
        <div class="form-group mb-4">
            <label class="form-label">Judul Artikel</label>
            <input type="text" name="title" class="form-input" placeholder="7 Tips Menjaga Kesehatan Jantung..." required>
        </div>
        <div class="grid grid-2 mb-4">
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select" required>
                    <option value="kesehatan_umum">Kesehatan Umum</option>
                    <option value="gizi">Gizi & Nutrisi</option>
                    <option value="olahraga">Olahraga</option>
                    <option value="mental_health">Kesehatan Mental</option>
                    <option value="tips_dokter">Tips Dokter</option>
                    <option value="penyakit">Penyakit</option>
                    <option value="ibu_anak">Ibu & Anak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status Terbit</label>
                <select name="is_published" class="form-select">
                    <option value="1">Langsung Terbit (Published)</option>
                    <option value="0">Draft (Belum Terbit)</option>
                </select>
            </div>
        </div>
        <div class="form-group mb-4">
            <label class="form-label">Ringkasan (Excerpt)</label>
            <textarea name="excerpt" class="form-input" rows="2" placeholder="Ringkasan singkat artikel..."></textarea>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">Isi Artikel (HTML didukung)</label>
            <textarea name="content" class="form-input" rows="12" placeholder="<p>Tulis artikel lengkap di sini...</p>" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">🚀 Terbitkan Artikel</button>
    </form>
</div>
@endsection
