<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | KoLine (Konsultasi Online)</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        {!! file_exists(public_path('css/app.css')) ? file_get_contents(public_path('css/app.css')) : '' !!}
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body>
<div class="app-layout" x-data="{ sidebarCollapsed: false }">
    {{-- ── Sidebar Navigation ───────────────────────── --}}
    <aside id="sidebar" class="sidebar" :class="{ 'collapsed': sidebarCollapsed }">
        <div class="sidebar-brand flex-between items-center">
            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : route('home'))) : route('home') }}" class="navbar-brand sidebar-brand-logo-wrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    <path d="M12 9v6M9 12h6"/>
                </svg>
                <span class="sidebar-brand-text">KoLine</span>
            </a>
            <button type="button" @click="sidebarCollapsed = !sidebarCollapsed" class="sidebar-toggle-btn" title="Buka/Tutup Sidebar">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- User Profile Header --}}
        <div class="user-profile-box">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar-img">
                <div class="user-info-text" style="min-width: 0; flex: 1;">
                    <div style="font-weight: 700; color: var(--txt-heading); font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.25;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 0.75rem; color: var(--txt-muted); font-weight: 500; margin-top: 0.15rem;">
                        {{ auth()->user()->role === 'admin' ? 'Administrator' : (auth()->user()->role === 'doctor' ? 'Dokter Spesialis' : 'Pasien') }}
                    </div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            {{-- Patient Nav --}}
            @if(auth()->user()->isPatient())
                <div class="sidebar-nav-section-title" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--txt-muted); padding: 0.2rem 0.5rem 0.25rem;">Layanan Medis</div>
                <a href="{{ route('consultations.index') }}" class="sidebar-link {{ request()->routeIs('consultations.*') ? 'active' : '' }}" title="Konsultasi Saya">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    <span class="sidebar-link-text">Konsultasi Saya</span>
                </a>
                <a href="{{ route('doctors.index') }}" class="sidebar-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}" title="Cari Dokter">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="sidebar-link-text">Cari Dokter</span>
                </a>
                <div class="sidebar-nav-section-title" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--txt-muted); padding: 0.2rem 0.5rem 0.25rem;">Kesehatan</div>
                <a href="{{ route('health-check.index') }}" class="sidebar-link {{ request()->routeIs('health-check.*') ? 'active' : '' }}" title="Cek Kesehatan">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="sidebar-link-text">Cek Kesehatan</span>
                </a>
                <a href="{{ route('medicines.index') }}" class="sidebar-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}" title="Apotek Digital">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M10.5 20.5l-7-7a6.364 6.364 0 018.999-9l7 7a6.364 6.364 0 01-8.999 9z"/><line x1="8.5" y1="8.5" x2="15.5" y2="15.5"/></svg>
                    <span class="sidebar-link-text">Apotek Digital</span>
                </a>
                <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" title="Pesanan Saya">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="sidebar-link-text">Pesanan Saya</span>
                </a>
            @endif

            {{-- Doctor Nav --}}
            @if(auth()->user()->isDoctor())
                <div class="sidebar-nav-section-title" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--txt-muted); padding: 0.2rem 0.5rem 0.25rem;">Panel Dokter</div>
                <a href="{{ route('doctor.dashboard') }}" class="sidebar-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" title="Dashboard Dokter">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="sidebar-link-text">Dashboard</span>
                </a>
                <a href="{{ route('consultations.index') }}" class="sidebar-link {{ request()->routeIs('consultations.*') ? 'active' : '' }}" title="Daftar Konsultasi">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    <span class="sidebar-link-text">Daftar Konsultasi</span>
                </a>
            @endif

            {{-- Admin Nav --}}
            @if(auth()->user()->isAdmin())
                <div class="sidebar-nav-section-title" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--txt-muted); padding: 0.2rem 0.5rem 0.25rem;">Panel Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard Admin">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="sidebar-link-text">Dashboard Admin</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" title="Manajemen User">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="sidebar-link-text">Manajemen User</span>
                </a>
                <a href="{{ route('admin.dokter.index') }}" class="sidebar-link {{ request()->routeIs('admin.dokter.*') ? 'active' : '' }}" title="Manajemen Dokter">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="sidebar-link-text">Manajemen Dokter</span>
                </a>
                <a href="{{ route('admin.apotek.index') }}" class="sidebar-link {{ request()->routeIs('admin.apotek.*') ? 'active' : '' }}" title="Manajemen Apotek">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M10.5 20.5l-7-7a6.364 6.364 0 018.999-9l7 7a6.364 6.364 0 01-8.999 9z"/><line x1="8.5" y1="8.5" x2="15.5" y2="15.5"/></svg>
                    <span class="sidebar-link-text">Manajemen Apotek</span>
                </a>
            @endif

            {{-- Account --}}
            <div class="sidebar-nav-section-title" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--txt-muted); padding: 0.2rem 0.5rem 0.25rem;">Akun Saya</div>
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" title="Profil Saya">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="sidebar-link-text">Profil Saya</span>
            </a>
            @if(auth()->user()->isPatient())
                <a href="{{ route('home') }}" class="sidebar-link" title="Beranda Depan">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="sidebar-link-text">Beranda Depan</span>
                </a>
            @endif
        </nav>

        <div style="padding: 1rem 1.25rem 1.5rem; border-top: 1px solid var(--bdr-subtle);">
            <a href="{{ route('logout') }}" class="sidebar-link" style="width:100%; color:var(--clr-danger);" title="Keluar Akun">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="sidebar-link-text">Keluar Akun</span>
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="main-content" :class="{ 'collapsed': sidebarCollapsed }">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-auto-dismiss mb-6">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error alert-auto-dismiss mb-6">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

{{-- KoLine AI Floating Assistant --}}
<x-ai-chatbot />

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
