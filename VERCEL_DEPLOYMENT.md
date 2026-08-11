# 🚀 Panduan Deploy KoLine di Vercel

Dokumen ini berisi panduan lengkap untuk melakukan penggelaran (deployment) aplikasi **KoLine (Konsultasi Online)** di platform **Vercel**.

---

### 📋 Struktur File Vercel yang Telah Disiapkan:
1. **`vercel.json`**: Konfigurasi serverless function runtime (`vercel-php`), routing aset statis (`/css/*`, `/js/*`, `/images/*`), serta environment variable bawaan.
2. **`api/index.php`**: Entrypoint serverless function yang secara otomatis:
   - Membuat direktori penyimpanan sementara di `/tmp/storage/` (views, sessions, cache).
   - Menyalin database SQLite bawaan yang terisi data (`database/database.sqlite`) ke `/tmp/database.sqlite`.

---

### 🛠️ Langkah Deployment di Dashboard Vercel:

1. Buka [https://vercel.com](https://vercel.com) dan login ke akun Vercel Anda.
2. Klik **Add New...** → **Project**.
3. Hubungkan akun GitHub Anda dan pilih repository: **`Faridzzz24/koline`**.
4. Pada bagian **Framework Preset**, pilih **Other** (atau biarkan default).
5. Pada bagian **Environment Variables**, tambahkan variabel berikut:
   - `APP_KEY` = `base64:UO7Dsorfnsg+OTiz9UzA3BiScuIw3PDqlalv7IejGvE=`
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `true`
   - `DB_CONNECTION` = `sqlite`
   - `SESSION_DRIVER` = `cookie`
   - `CACHE_STORE` = `array`
6. Klik **Deploy**!

---

### 🔑 Akun Default di Production Vercel:
- **Admin**: `admin@koline.test` | Password: `admin123`
- **Dokter**: `andi.wijaya@koline.test` | Password: `password123`
- **Pasien**: `pasien@koline.test` | Password: `pasien123`
