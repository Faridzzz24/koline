<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru | KoLine (Konsultasi Online)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body style="display: flex; min-height: 100vh; background: var(--bg-dark); align-items: center; justify-content: center; padding: 3rem 1.5rem;">

    <div style="width: 100%; max-width: 620px;">
        {{-- Brand Logo & Title --}}
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <a href="{{ route('home') }}" class="navbar-brand mb-4">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    <path d="M12 9v6M9 12h6"/>
                </svg>
                <span>KoLine</span>
            </a>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.3;">Pendaftaran Akun Baru</h1>
            <p style="color: var(--txt-muted); font-size: 0.95rem;">Bergabung dengan platform konsultasi medis terpercaya KoLine</p>
        </div>

        {{-- Form Container --}}
        <div class="card" style="padding: 2.5rem 2.75rem;">
            <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
                @csrf

                <div class="grid grid-2" style="gap: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nama Lengkap (Sesuai KTP)</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input {{ $errors->has('name') ? 'is-error' : '' }}" placeholder="Nama Lengkap" required autofocus>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nomor Telepon / WA</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="081234567890">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Alamat Email Active</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" placeholder="nama@email.com" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="grid grid-2" style="gap: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-input">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="gender" class="form-select">
                            <option value="">Pilih Jenis Kelamin...</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-2" style="gap: 1.5rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Kata Sandi (Password)</label>
                        <div x-data="{ show: false }" style="position: relative;">
                            <input :type="show ? 'text' : 'password'" name="password" class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" placeholder="Minimal 8 karakter" required autocomplete="new-password" style="padding-right: 2.75rem;">
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
                        <label class="form-label">Konfirmasi Kata Sandi</label>
                        <div x-data="{ show: false }" style="position: relative;">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" class="form-input" placeholder="Ulangi password" required style="padding-right: 2.75rem;">
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

                <div style="padding: 1.125rem; background: var(--bg-surface); border-radius: var(--r-md); border: 1px solid var(--bdr-subtle); margin-top: 0.25rem;">
                    <p style="font-size: 0.8rem; color: var(--txt-muted); line-height: 1.6; margin-bottom: 0;">
                        Dengan mendaftar, Anda menyetujui <a href="#" style="color: var(--clr-brand-light); font-weight: 600;">Syarat & Ketentuan Service Medis</a> dan <a href="#" style="color: var(--clr-brand-light); font-weight: 600;">Kebijakan Privasi Data Pasien</a> KoLine.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 0.5rem; height: 50px;">
                    Daftar Akun KoLine Sekarang
                </button>
            </form>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <span style="color: var(--txt-muted); font-size: 0.925rem;">Sudah memiliki akun KoLine? </span>
            <a href="{{ route('login') }}" style="color: var(--clr-brand-light); font-weight: 600; font-size: 0.925rem;">Masuk Sesi</a>
        </div>
    </div>

</body>
</html>
