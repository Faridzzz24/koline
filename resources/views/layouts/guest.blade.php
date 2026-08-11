<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'KoLine - Platform kesehatan digital terpadu. Konsultasi dokter spesialis, 10+ alat cek kesehatan mandiri, dan layanan apotek digital 24/7.')">
    <title>@yield('title', 'Beranda') | KoLine</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        {!! file_exists(public_path('css/app.css')) ? file_get_contents(public_path('css/app.css')) : '' !!}
    </style>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body x-data="{ healthOpen: false }">
    {{-- ── Header & Navbar ───────────────────────────── --}}
    <nav id="navbar" class="navbar">
        <div class="container">
            <div class="navbar-inner">
                {{-- Left: Brand Logo --}}
                <a href="{{ route('home') }}" class="navbar-brand">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path d="M12 9v6M9 12h6"/>
                    </svg>
                    <span>KoLine</span>
                </a>

                {{-- Center: Nav Links (hidden on mobile) --}}
                <ul id="nav-menu" class="navbar-nav">
                    <li>
                        <a href="{{ route('doctors.index') }}" class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                            Cari Dokter
                        </a>
                    </li>

                    {{-- Cek Kesehatan Dropdown Popover (Smooth Hover & Continuous Bridge) --}}
                    <li class="mega-dropdown-wrapper"
                        @mouseenter="healthOpen = true"
                        @mouseleave.debounce.300ms="healthOpen = false"
                        @click.outside="healthOpen = false">
                        <a href="{{ route('health-check.index') }}" class="nav-link {{ request()->routeIs('health-check.*') ? 'active' : '' }}">
                            Cek Kesehatan Mandiri
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition: transform 0.2s;" :style="healthOpen ? 'transform: rotate(180deg)' : ''"><path d="m6 9 6 6 6-6"/></svg>
                        </a>

                        {{-- Mega Dropdown Popover Grid (10 Interactive Tools) --}}
                        <div class="mega-dropdown-menu">
                            {{-- 1. Cek Stres --}}
                            <a href="{{ route('health-check.stres') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-4a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <div class="health-tool-title">Cek Stres</div>
                            </a>

                            {{-- 2. Kalkulator BMI --}}
                            <a href="{{ route('health-check.bmi') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6l3 18h12l3-18H3z"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                                </div>
                                <div class="health-tool-title">Kalkulator BMI</div>
                            </a>

                            {{-- 3. Risiko Jantung --}}
                            <a href="{{ route('health-check.jantung') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                                <div class="health-tool-title">Risiko Jantung</div>
                            </a>

                            {{-- 4. Risiko Diabetes --}}
                            <a href="{{ route('health-check.diabetes') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                </div>
                                <div class="health-tool-title">Risiko Diabetes</div>
                            </a>

                            {{-- 5. Tes Depresi --}}
                            <a href="{{ route('health-check.depresi') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="health-tool-title">Tes Depresi</div>
                            </a>

                            {{-- 6. Tes Kecemasan --}}
                            <a href="{{ route('health-check.kecemasan') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="health-tool-title">Tes Kecemasan</div>
                            </a>

                            {{-- 7. Kalender Menstruasi --}}
                            <a href="{{ route('health-check.menstruasi') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="health-tool-title">Kalender Menstruasi</div>
                            </a>

                            {{-- 8. Pengingat Obat --}}
                            <a href="{{ route('health-check.pengingat-obat') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="health-tool-title">Pengingat Obat</div>
                            </a>

                            {{-- 9. Kalender Kehamilan --}}
                            <a href="{{ route('health-check.kehamilan') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div class="health-tool-title">Kalender Kehamilan</div>
                            </a>

                            {{-- 10. Donasi Medis --}}
                            <a href="{{ route('health-check.donasi') }}" class="health-tool-item">
                                <div class="health-tool-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                </div>
                                <div class="health-tool-title">Donasi Medis</div>
                            </a>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                            Apotek Digital
                        </a>
                    </li>
                </ul>

                {{-- Right: Action Buttons --}}
                <div class="navbar-actions">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm mobile-hide-register">Daftar</a>
                    @endguest

                    @auth
                        <a href="{{ route('cart.index') }}" class="btn btn-ghost btn-icon" style="position:relative;" title="Keranjang Belanja">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                            @php $cartCount = count(session('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span style="position:absolute;top:0;right:0;background:var(--clr-brand);color:white;font-size:0.6rem;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <div class="dropdown" id="user-dropdown-container">
                            <button type="button" id="user-profile-toggle-btn" class="user-avatar-btn">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="avatar">
                                <span class="user-name-text">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                <svg class="mobile-hide-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="dropdown-menu" id="user-profile-menu-box">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Panel Admin</a>
                                @endif
                                @if(auth()->user()->isDoctor())
                                    <a href="{{ route('doctor.dashboard') }}" class="dropdown-item">Dashboard Dokter</a>
                                @endif
                                <a href="{{ route('consultations.index') }}" class="dropdown-item">Konsultasi Saya</a>
                                <a href="{{ route('orders.index') }}" class="dropdown-item">Pesanan Apotek</a>
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">Pengaturan Profil</a>
                                <div style="height: 1px; background: var(--bdr-subtle); margin: 0.25rem 0;"></div>
                                <a href="{{ route('logout') }}" class="dropdown-item" style="color:var(--clr-danger);">Keluar Akun</a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ── Mobile Bottom Nav Bar ──────────────────────── --}}
    <nav class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('doctors.index') }}" class="mobile-nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Dokter</span>
        </a>
        <a href="{{ route('health-check.index') }}" class="mobile-nav-item {{ request()->routeIs('health-check.*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Cek Sehat</span>
        </a>
        <a href="{{ route('medicines.index') }}" class="mobile-nav-item {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            <span>Apotek</span>
        </a>
    </nav>

    {{-- Flash Notifications --}}
    @if(session('success') || session('error') || session('info'))
        <div style="position:fixed;top:80px;left:50%;transform:translateX(-50%);z-index:9999;width:90%;max-width:480px;">
            @if(session('success'))
                <div class="alert alert-success alert-auto-dismiss">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error alert-auto-dismiss">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-auto-dismiss">{{ session('info') }}</div>
            @endif
        </div>
    @endif

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <div class="navbar-brand mb-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                            <path d="M12 9v6M9 12h6"/>
                        </svg>
                        <span>KoLine</span>
                    </div>
                    <p style="color:var(--txt-muted);font-size:0.875rem;line-height:1.7;max-width:340px;">Platform kesehatan digital resmi untuk akses konsultasi medis terpercaya, pemantauan kesehatan mandiri, dan pengadaan produk farmasi.</p>
                </div>

                <div class="footer-column">
                    <div class="footer-heading">Layanan Medis</div>
                    <div class="footer-links">
                        <a href="{{ route('doctors.index') }}" class="footer-link">Dokter Spesialis</a>
                        <a href="{{ route('health-check.bmi') }}" class="footer-link">Kalkulator BMI</a>
                        <a href="{{ route('health-check.symptom') }}" class="footer-link">Analisis Gejala</a>
                        <a href="{{ route('medicines.index') }}" class="footer-link">Apotek Digital</a>
                    </div>
                </div>

                <div class="footer-column">
                    <div class="footer-heading">Informasi</div>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Tentang Platform</a>
                        <a href="#" class="footer-link">Pusat Bantuan</a>
                    </div>
                </div>

                <div class="footer-column">
                    <div class="footer-heading">Keamanan</div>
                    <div style="margin-bottom: 0.5rem;">
                        <span class="badge badge-teal">Terverifikasi Medis</span>
                    </div>
                    <p style="font-size:0.8rem;color:var(--txt-muted);line-height:1.6;">Seluruh data konsultasi dan rekam medis terenkripsi dan terlindungi dengan standar tinggi.</p>
                </div>
            </div>

            <div style="border-top:1px solid var(--bdr-subtle);padding-top:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                <p style="font-size:0.8125rem;color:var(--txt-muted);">© {{ date('Y') }} KoLine. All rights reserved.</p>
                <p style="font-size:0.8125rem;color:var(--txt-muted);">Platform Layanan Kesehatan Daring Terpadu</p>
            </div>
        </div>
    </footer>

    {{-- KoLine AI Floating Assistant --}}
    <x-ai-chatbot />

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userBtn = document.getElementById('user-profile-toggle-btn');
        const userMenu = document.getElementById('user-profile-menu-box');
        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                userMenu.classList.toggle('active');
            });
            document.addEventListener('click', function(e) {
                if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.remove('active');
                }
            });
        }
    });
    </script>
    @stack('scripts')
</body>
</html>
