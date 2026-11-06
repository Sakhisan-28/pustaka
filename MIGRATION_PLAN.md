# MIGRATION PLAN — Next.js → Laravel

Checklist langkah migrasi dari Next.js 14 + Drizzle + NextAuth ke Laravel 11 + Livewire + Filament.

---

## 1. Backup Awal

- [ ] Backup semua file project (git commit atau copy folder project)
- [ ] Simpan `PRD.md` dan `AGENTS.md` lama sebagai baseline
- [ ] Backup `.env` (nilai nyata, jangan commit)

## 2. Bersihkan Project Lama

- [ ] Hapus folder `node_modules/`
- [ ] Hapus folder `.next/`
- [ ] Hapus file: `next.config.js`, `tailwind.config.ts`, `tsconfig.json`, `.eslintrc.json`, `next-env.d.ts`, `package.json`, `package-lock.json`
- [ ] Hapus folder `src/`
- [ ] Hapus folder `drizzle/`
- [ ] Hapus file `next-env.d.ts`, `postcss.config.js` (jika ada)

## 3. Install Laravel 11 Fresh

- [ ] `composer create-project laravel/laravel:^11 .` (pakai titik agar tetap di folder `pustaka`, preserve file .md + .git)
- [ ] `npm install` (untuk Vite + Tailwind)
- [ ] `npm install -D tailwindcss postcss autoprefixer`
- [ ] Setup `tailwind.config.js` dengan content `./resources/**/*.{blade.php,js}`
- [ ] Edit `resources/css/app.css` tambahkan `@tailwind base; @tailwind components; @tailwind utilities;`
- [ ] `composer require livewire/livewire`
- [ ] `composer require filament/filament:"^3.2" -W`
- [ ] `php artisan filament:install --panels`
- [ ] Konfigurasi database di `.env`

## 4. Migrasi Env Vars

| Env Var | Next.js (Lama) | Laravel (Baru) |
|---|---|---|
| Database | `DATABASE_URL` | `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`, `DB_PORT` |
| Auth | `AUTH_SECRET`, `AUTH_GOOGLE_ID`, `AUTH_GOOGLE_SECRET` | `APP_KEY`, `SESSION_DRIVER`, `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `SESSION_DOMAIN` |
| R2 | `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET_NAME`, `R2_PUBLIC_URL` | `FILESYSTEM_DISK=r2`, `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET_NAME` |
| Resend | `RESEND_API_KEY` | `RESEND_API_KEY`, `MAIL_MAILER=resend`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` |

## 5. Migrasi Database

- [ ] JANGAN jalankan `php artisan migrate:fresh` sebelum memastikan semua data sementara sudah dipindah
- [ ] Copy tabel `books`, `categories`, `borrowings`, `reservations`, `reading_progress`, `bookmarks` dari migration lama (Next.js/Drizzle) — bisa pakai file SQL yang dihasilkan `drizzle/`
- [ ] Hapus tabel NextAuth (`accounts`, `sessions`, `verification_tokens`) — Laravel Breeze/Socialite handle auth sendiri
- [ ] Skema user table: tambah `role` (enum member/admin), `google_id` (string nullable), `avatar` (string nullable)

## 6. Migrasi Fitur

- [ ] Auth: dari Auth.js → Laravel Breeze + Socialite (email/password + Google OAuth)
- [ ] Katalog: dari Next.js App Router pages → Laravel routes + Livewire components
- [ ] Reader: dari PDF.js/epub.js + Next.js → PDF.js/epub.js + Livewire/Breeze + Vite
- [ ] Sirkulasi: dari API routes (Next.js) → Laravel controllers + Livewire actions
- [ ] Admin: dari shadcn/ui custom → Filament 3 (BookResource, UserResource, LaporanResource)
- [ ] Email: dari Resend (Next.js) → Laravel Mail + Resend (Mailable + Notification)

## 7. Testing

- [ ] Pastikan `./vendor/bin/pest` lolos
- [ ] Pastikan `php artisan route:list` menampilkan semua route
- [ ] Pastikan fitur auth (register/login/Google) bisa diuji via browser
- [ ] Pastikan fitur pinjam/kembali bekerja via Livewire actions

---

## Ringkasan Perubahan

### Yang Berubah:
- Next.js 14 → Laravel 11
- Drizzle ORM → Eloquent ORM
- NextAuth v5 → Laravel Breeze + Socialite
- React + shadcn/ui → Livewire 3 + Blade + Filament 3
- Vercel → VPS / Railway / Laravel Forge
- Vercel Cron → Laravel Scheduler
- Vitest + Playwright → Pest PHP
- PDF.js/epub.js asset management → Vite (Laravel)

### Yang Tetap Sama:
- Tema visual Warm Neutral (Lora + Inter, warna tokens)
- Schema DB PostgreSQL (tabel books, borrowings, dll. sama)
- Fitur aplikasi (baca, pinjam, denda, reservasi)
- R2 sebagai storage utama

### Langkah Berikutnya:
1. Buat file ini (sedang dikerjakan)
2. Jadi perintah: `composer create-project laravel/laravel:^11 .`
3. Konfigurasi `.env` dengan nilai yang sama
4. Migrasikan database
5. Migrasikan fitur satu per satu
6. Deploy ke VPS/Railway/Forge

## Rollback Plan

- [ ] Kalau mau balik ke Next.js: `git checkout main && git branch -D migration/laravel && rm -rf vendor/ node_modules/ && npm install`
