@extends('layouts.app')
@section('title', 'Manajemen Dokter | KoLine')

@section('content')
<div style="max-width: 100%;">

    <div class="main-header mb-6 flex-between items-center">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Manajemen Dokter Spesialis</h1>
            <div style="font-size: 0.9rem; color: #94A3B8;">Kelola data dokter, verifikasi STR, dan tarif telekonsultasi</div>
        </div>
        <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary">+ Tambah Dokter Baru</a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: left; padding-left: 1.5rem;">Profil Dokter</th>
                    <th style="text-align: center;">Spesialisasi</th>
                    <th style="text-align: center;">No. STR</th>
                    <th style="text-align: center;">Tarif Sesi</th>
                    <th style="text-align: center;">Status Sesi</th>
                    <th style="text-align: right; padding-right: 1.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $d)
                    @php
                        $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $d->user->name);
                        $words = explode(' ', trim($cleanName));
                        $initials = strtoupper(substr($words[0] ?? 'D', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <tr>
                        <td style="text-align: left; padding-left: 1.5rem;">
                            <div class="flex items-center gap-3">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(2, 132, 199, 0.15); color: var(--clr-brand-light); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; border: 1px solid rgba(2, 132, 199, 0.3); flex-shrink: 0;">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #F8FAFC; font-size: 0.925rem;">{{ $d->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #CBD5E1;">{{ $d->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span style="color: var(--clr-brand-light); font-weight: 600; font-size: 0.875rem;">{{ $d->specialization->name }}</span>
                        </td>
                        <td style="text-align: center; font-family: monospace; font-size: 0.85rem; color: #CBD5E1; white-space: nowrap;">{{ $d->str_number }}</td>
                        <td style="text-align: center; font-weight: 700; color: #10B981; white-space: nowrap;">{{ $d->formatted_fee }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.75rem; align-items: center; justify-content: center; white-space: nowrap;">
                                <span style="color: {{ $d->is_available ? '#34D399' : '#94A3B8' }}; font-size: 0.825rem; font-weight: 600;">
                                    {{ $d->is_available ? '● Online' : '○ Offline' }}
                                </span>
                                <span style="color: {{ $d->is_verified ? '#38BDF8' : '#FBBF24' }}; font-size: 0.825rem; font-weight: 600;">
                                    {{ $d->is_verified ? '✓ Terverifikasi' : 'Belum' }}
                                </span>
                            </div>
                        </td>
                        <td style="text-align: right; padding-right: 1.5rem;">
                            <div class="flex items-center gap-2" style="justify-content: flex-end; white-space: nowrap;">
                                <a href="{{ route('admin.dokter.edit', $d) }}" class="btn btn-outline btn-sm" style="font-size: 0.775rem;">Edit</a>
                                <form action="{{ route('admin.dokter.update', $d) }}" method="POST" style="margin: 0;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="is_verified" value="{{ $d->is_verified ? 0 : 1 }}">
                                    <button class="btn btn-outline btn-sm" style="font-size: 0.775rem;">{{ $d->is_verified ? 'Unverify' : 'Verify' }}</button>
                                </form>
                                <form action="{{ route('admin.dokter.destroy', $d) }}" method="POST" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus data dokter ini?')" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-ghost btn-sm" style="color: var(--clr-danger); font-size: 0.775rem;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($doctors->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;">
            {{ $doctors->links('vendor.pagination.custom') }}
        </div>
    @endif

</div>
@endsection
