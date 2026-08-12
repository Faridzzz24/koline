@extends('layouts.app')
@section('title', 'Manajemen User | KoLine')

@section('content')
<div style="max-width: 100%;">

    <div class="main-header mb-6" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">Manajemen Pengguna</h1>
            <div style="font-size: 0.9rem; color: #94A3B8;">Kelola data pasien, akun dokter, dan akses administrator platform KoLine</div>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah Akun Baru</a>
    </div>

    {{-- Executive Filter & Search Bar --}}
    <form action="{{ route('admin.users.index') }}" method="GET" class="card mb-6" style="padding: 1.125rem 1.5rem; background: var(--bg-card); border: 1px solid var(--bdr-subtle);">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            
            {{-- Search Input with SVG Search Icon --}}
            <div style="flex: 1; min-width: 260px; position: relative;">
                <svg width="18" height="18" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna atau email..." class="form-input" style="padding-left: 2.75rem; margin-bottom: 0;">
            </div>

            {{-- Role Filter Select --}}
            <div style="width: 180px;">
                <select name="role" class="form-select" style="margin-bottom: 0;">
                    <option value="">Semua Role</option>
                    <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Pasien</option>
                    <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Dokter</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm" style="color: #94A3B8;">Reset</a>
                @endif
            </div>

        </div>
    </form>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: left; padding-left: 1.5rem;">Pengguna</th>
                    <th style="text-align: left;">Email</th>
                    <th style="text-align: center;">Role</th>
                    <th style="text-align: center;">Bergabung</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $user->name);
                        $words = explode(' ', trim($cleanName));
                        $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <tr>
                        <td style="text-align: left; padding-left: 1.5rem;">
                            <div class="flex items-center gap-3">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(2, 132, 199, 0.15); color: var(--clr-brand-light); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; border: 1px solid rgba(2, 132, 199, 0.3); flex-shrink: 0;">
                                    {{ $initials }}
                                </div>
                                <div style="font-weight: 700; color: #F8FAFC;">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td style="color: #CBD5E1; font-weight: 500; text-align: left;">{{ $user->email }}</td>
                        <td style="text-align: center;">
                            @if($user->isAdmin())
                                <span style="color: #FB7185; font-weight: 600; font-size: 0.875rem;">Administrator</span>
                            @elseif($user->isDoctor())
                                <span style="color: #38BDF8; font-weight: 600; font-size: 0.875rem;">Dokter Spesialis</span>
                            @else
                                <span style="color: #2DD4BF; font-weight: 500; font-size: 0.875rem;">Pasien</span>
                            @endif
                        </td>
                        <td style="color: #CBD5E1; font-size: 0.875rem; text-align: center;">{{ $user->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <div class="flex items-center gap-2" style="justify-content: center;">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.85rem; font-size: 0.8rem;">Edit</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirmDelete(event, 'Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--clr-danger); padding: 0.35rem 0.85rem; font-size: 0.8rem;">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 0.775rem; color: var(--txt-muted); font-weight: 500;">Akun Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--txt-muted);">
                            Tidak ada pengguna ditemukan dengan kata kunci atau filter tersebut.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    @endif

</div>
@endsection
