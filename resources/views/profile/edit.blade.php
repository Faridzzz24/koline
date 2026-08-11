@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')

<div style="text-align: center; margin-bottom: 1.25rem;">
    <h1 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.35rem; text-align: center;">Pengaturan Profil</h1>
    <div style="font-size: 0.9rem; color: var(--txt-muted); text-align: center;">Kelola data pribadi, alamat, dan keamanan akun Anda</div>
</div>

<div style="max-width: 860px; margin: 0 auto;">
    <div class="card mb-8">
        {{-- Profile Header Bar --}}
        <div class="flex items-center gap-6 mb-8 pb-6" style="border-bottom: 1px solid var(--bdr-subtle);">
            @php
                $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', auth()->user()->name);
                $words = explode(' ', trim($cleanName));
                $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            @endphp
            <div class="initial-avatar initial-avatar-lg">
                {{ $initials }}
            </div>
            <div>
                <div style="font-size: 1.35rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 0.25rem;">{{ auth()->user()->name }}</div>
                <span class="badge badge-teal">{{ ucfirst(auth()->user()->role) }}</span>
                <div style="font-size: 0.875rem; color: var(--txt-muted); margin-top: 0.5rem;">{{ auth()->user()->email }}</div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="grid grid-2" style="gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-input" placeholder="081234567890">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}" class="form-input">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="gender" class="form-select">
                        <option value="">Pilih Jenis Kelamin...</option>
                        <option value="male" {{ auth()->user()->gender === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ auth()->user()->gender === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Golongan Darah</label>
                    <select name="blood_type" class="form-select">
                        <option value="">Pilih Golongan Darah...</option>
                        @foreach(['A','B','AB','O'] as $bt)
                            <option value="{{ $bt }}" {{ auth()->user()->blood_type === $bt ? 'selected' : '' }}>Golongan Darah {{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mb-6">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" class="form-input" rows="3" placeholder="Alamat pengiriman / domisili...">{{ old('address', auth()->user()->address) }}</textarea>
            </div>

            <div class="divider"></div>

            <h4 style="margin-bottom: 1.25rem;">Ubah Password Akun <small style="font-weight: 400; color: var(--txt-muted); font-size: 0.85rem;">(Kosongkan jika tidak ingin diubah)</small></h4>
            <div class="grid grid-2 mb-8" style="gap: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Password Baru</label>
                    <div x-data="{ show: false }" style="position: relative;">
                        <input :type="show ? 'text' : 'password'" name="password" class="form-input" placeholder="Minimal 8 karakter" style="padding-right: 2.75rem;">
                        <button type="button" @click="show = !show" style="position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--txt-muted); cursor: pointer; display: flex; align-items: center; padding: 0.25rem;">
                            <svg x-show="!show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;" x-cloak>
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div x-data="{ show: false }" style="position: relative;">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" style="padding-right: 2.75rem;">
                        <button type="button" @click="show = !show" style="position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--txt-muted); cursor: pointer; display: flex; align-items: center; padding: 0.25rem;">
                            <svg x-show="!show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;" x-cloak>
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Simpan Perubahan Profil</button>
        </form>
    </div>

    {{-- Medical Stats Card --}}
    @if(auth()->user()->isPatient())
        <div class="card">
            <h3 style="margin-bottom: 1.5rem;">Informasi Rekam Medis</h3>
            <div class="grid grid-4" style="gap: 1.25rem;">
                <div style="text-align: center; padding: 1.25rem; background: var(--bg-surface); border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);">
                    <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem;">Gol. Darah</div>
                    <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.25rem;">{{ auth()->user()->blood_type ?? '-' }}</div>
                </div>
                <div style="text-align: center; padding: 1.25rem; background: var(--bg-surface); border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);">
                    <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem;">Usia</div>
                    <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.25rem;">{{ auth()->user()->age ? auth()->user()->age . ' Thn' : '-' }}</div>
                </div>
                <div style="text-align: center; padding: 1.25rem; background: var(--bg-surface); border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);">
                    <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem;">Total Konsultasi</div>
                    <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.25rem;">{{ auth()->user()->consultationsAsPatient->count() }}</div>
                </div>
                <div style="text-align: center; padding: 1.25rem; background: var(--bg-surface); border-radius: var(--r-lg); border: 1px solid var(--bdr-subtle);">
                    <div style="font-size: 0.75rem; color: var(--txt-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem;">Cek Kesehatan</div>
                    <div style="font-weight: 800; color: var(--txt-heading); font-size: 1.25rem;">{{ auth()->user()->healthChecks->count() }}</div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
