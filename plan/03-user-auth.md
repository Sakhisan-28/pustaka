# 03 — Autentikasi User (Breeze + Google Socialite)

> **Status:** ✅ Done (2026-11-13) — Auth Breeze (register/login/logout) berfungsi. Google Socialite sudah ter-scaffold, **tinggal isi `GOOGLE_CLIENT_ID` & `GOOGLE_CLIENT_SECRET`** (Google Cloud Console) agar tombol "Masuk dengan Google" aktif.

## Tujuan
Menyediakan register & login email/password (Laravel Breeze) serta login via Google OAuth (Socialite) untuk user biasa, dengan role default `member`.

## Prasyarat
- Laravel project & database jalan — ✅ sudah ada.
- Tabel `users` punya kolom `role`, `google_id`, `avatar` — ✅ sudah ada.
- Model `User` `$fillable` sudah termasuk `role`, `avatar` — ✅ sudah ada.

## File yang Disentuh
- `composer.json` (via `composer require laravel/breeze` & `laravel/socialite`).
- `routes/auth.php` (dibuat oleh Breeze) — route auth.
- `app/Http/Controllers/Auth/*` (dibuat oleh Breeze) — controller auth.
- `resources/views/auth/*` (dibuat oleh Breeze) — view login/register.
- `resources/views/layouts/app.blade.php` (edit) — link login/register di header.
- `config/services.php` (edit) — tambah konfigurasi `google`.
- `.env` & `.env.example` (edit) — `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`.
- `app/Http/Controllers/Auth/GoogleController.php` (buat) — redirect + callback OAuth.
- `routes/web.php` (edit) — route `auth/google` & `auth/google/callback`.
- `app/Models/User.php` (edit) — helper `isAdmin()`.
- `app/Providers/Filament/AdminPanelProvider.php` (edit) — batasi akses panel hanya role `admin`.

## Langkah Eksekusi
- [x] `composer require laravel/breeze --dev` lalu `php artisan breeze:install blade`.
- [x] `composer require laravel/socialite`.
- [x] Tambah blok `google` di `config/services.php` (client_id, client_secret, redirect).
- [ ] Isi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` di `.env` (dari Google Cloud Console) — **menunggu kredensial dari user**.
- [x] Buat `GoogleController` (redirect + callback: buat/link user, set `google_id`, `avatar`, role `member`).
- [x] Tambah route `GET /auth/google` & `GET /auth/google/callback`.
- [x] Navbar: tamu → Login/Register; login → nama + Logout; admin → link Admin.
- [x] Redirect setelah login/register → `/katalog`.
- [x] Tema Warm Neutral pada layout guest & view auth (background `#FAF8F5`, Inter/Lora, primary biru).
- [x] Batasi panel Filament `/admin` hanya untuk role `admin` (User implements `FilamentUser::canAccessPanel`).
- [x] `php artisan migrate`, `php artisan optimize:clear`, `npm run build`.
- [x] Test register/login/logout (kernel render) + navbar state; Google login **pending kredensial**.

## Acceptance Criteria
- User bisa register & login dengan email/password.
- User bisa login dengan Google → akun dibuat/di-link otomatis (`google_id` terisi, avatar terisi).
- Setelah login, header menampilkan nama user + tombol logout.
- Role default `member`.
- User `member` **tidak bisa** membuka `/admin` (hanya `admin`).
- Reset password via email berfungsi (Breeze).