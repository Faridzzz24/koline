@extends('layouts.app')
@section('title', 'Manajemen Artikel')
@section('content')
<div class="main-header">
    <div><div class="page-title">📰 Manajemen Artikel</div></div>
    <a href="{{ route('admin.artikel.create') }}" class="btn btn-primary">+ Tambah Artikel</a>
</div>
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Views</th><th style="text-align: center;">Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($articles as $a)
                <tr>
                    <td><div style="font-weight:600;color:var(--txt-primary);">{{ Str::limit($a->title, 50) }}</div></td>
                    <td><span class="badge badge-muted">{{ $a->category_label }}</span></td>
                    <td>{{ $a->author->name }}</td>
                    <td><span class="badge {{ $a->is_published ? 'badge-success' : 'badge-warning' }}">{{ $a->is_published ? 'Terbit' : 'Draft' }}</span></td>
                    <td>{{ number_format($a->views) }}</td>
                    <td style="text-align: center;">
                        <div class="flex gap-2" style="justify-content: center;">
                            <a href="{{ route('admin.artikel.edit', $a) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('admin.artikel.destroy', $a) }}" method="POST" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links('vendor.pagination.custom') }}</div>
@endsection
