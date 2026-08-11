<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sesi | KoLine (Konsultasi Online)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="display: flex; min-height: 100vh; background: var(--bg-dark);">

    {{-- Left Visual Panel (Desktop) --}}
    <div style="flex: 1; background: radial-gradient(circle at 30% 40%, rgba(2, 132, 199, 0.15) 0%, transparent 60%), var(--bg-surface); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem; position: relative; border-right: 1px solid var(--bdr-subtle);">
        <div style="text-align: center; max-width: 440px;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: var(--r-xl); background: rgba(2, 132, 199, 0.15); margin-bottom: 1.5rem;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    <path d="M12 9v6M9 12h6"/>
                </svg>
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--txt-heading); margin-bottom: 1rem;">
                KoLine
            </h1>
            <p style="color: var(--txt-body); font-size: 1rem; line-height: 1.7; margin-bottom: 2.5rem;">
                Platform layanan kesehatan digital resmi untuk akses konsultasi dokter spesialis, analisis kesehatan mandiri, dan apotek digital terintegrasi.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1rem; text-align: left;">
                <div class="flex items-center gap-3" style="color: var(--txt-body); font-size: 0.9rem;">
                    <span style="color: var(--clr-teal-light); font-weight: 700;">✓</span> 2.500+ Dokter Spesialis Terverifikasi
                </div>
                <div class="flex items-center gap-3" style="color: var(--txt-body); font-size: 0.9rem;">
                    <span style="color: var(--clr-teal-light); font-weight: 700;">✓</span> Layanan Telemedis Siaga 24/7
                </div>
                <div class="flex items-center gap-3" style="color: var(--txt-body); font-size: 0.9rem;">
                    <span style="color: var(--clr-teal-light); font-weight: 700;">✓</span> Rekam Medis Terenkripsi & Safe Privacy
                </div>
            </div>
        </div>
    </div>

    {{-- Right Form Panel --}}
    <div style="width: 100%; max-width: 520px; display: flex; align-items: center; justify-content: center; padding: 3rem; background: var(--bg-dark);">
        <div style="width: 100%;">
            <div style="margin-bottom: 2.5rem;">
                <a href="{{ route('home') }}" class="navbar-brand mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path d="M12 9v6M9 12h6"/>
                    </svg>
                    <span>KoLine</span>
                </a>
                <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem;">Selamat Datang Kembali</h2>
                <p style="color: var(--txt-muted); font-size: 0.9rem;">Masuk ke akun KoLine Anda untuk melanjutkan.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-6">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Terdaftar</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <div class="flex-between mb-1">
                        <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: var(--clr-brand-light);">Lupa password?</a>
                        @endif
                    </div>
                    <div x-data="{ show: false }" style="position: relative;">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" class="form-input" placeholder="••••••••" required style="padding-right: 2.75rem;">
                        <button type="button" @click="show = !show" style="position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--txt-muted); cursor: pointer; display: flex; align-items: center; padding: 0.25rem;" aria-label="Toggle Password Visibility">
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

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember_me" name="remember" style="width: 16px; height: 16px; accent-color: var(--clr-brand); cursor: pointer;">
                    <label for="remember_me" style="font-size: 0.875rem; color: var(--txt-body); cursor: pointer;">Ingat sesi saya</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 0.5rem; height: 48px;">
                    Masuk ke KoLine
                </button>
            </form>

            <div style="text-align: center; margin-top: 2rem;">
                <span style="color: var(--txt-muted); font-size: 0.9rem;">Belum memiliki akun? </span>
                <a href="{{ route('register') }}" style="color: var(--clr-brand-light); font-weight: 600; font-size: 0.9rem;">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
</body>
</html>
