# AGENTS.md — Pustaka (Laravel)

> Digital library web app. Only source of truth: `PRD_LARAVEL.md`. No code exists yet.

---

## Tech Stack (from PRD_LARAVEL §8)

| Layer | Decision |
|-------|----------|
| Framework | Laravel 11 |
| Frontend interaktif | Livewire 3 (Blade, tidak React) |
| Panel Admin | Filament 3 |
| Styling | Tailwind CSS 3 |
| Database | PostgreSQL (Neon) |
| ORM | Eloquent (bawaan Laravel) |
| Auth | Laravel Breeze (Blade) + Laravel Socialite (Google OAuth) |
| File Storage | Laravel Filesystem + Cloudflare R2 (S3-compatible, via league/flysystem-aws-s3-v3) |
| PDF Reader | PDF.js (Mozilla) |
| EPUB Reader | epub.js (Future Press) |
| Email | Laravel Mail + Resend |
| Deploy | VPS / Railway / Laravel Forge |
| Cron | Laravel Scheduler (`/protected/scheduler`) |
| Testing | Pest PHP |

**Key constraint**: "Warm Neutral" theme (PRD_LARAVEL §6) — Lora serif for headings, Inter for UI, specific color tokens.

---

## Project Phases (PRD_LARAVEL §10)

| Phase | Duration | Deliverable | Dependencies |
|-------|----------|-------------|--------------|
| 0: Discovery | 1 wk | Resolve open questions, finalize DB schema, pick storage | — |
| 1: Foundation & Auth | 1 wk | Laravel project, DB, Auth (Breeze + Socialite), RBAC | Phase 0 |
| 2: Katalog & Admin CRUD | 1.5 wk | Book schema, katalog UI, Filament CRUD + upload | Phase 1 |
| 3: Reader & Bookshelf | 3 wk | PDF.js, epub.js, progress tracking, bookmark, bookshelf UI | Phase 2 |
| 4: Sirkulasi | 2 wk | Pinjam/kembali, reservasi, extend, cron auto-return, notifikasi email | Phase 1, 2 |
| 5: Admin Dashboard | 0.5 wk | Filament panel admin, user mgmt, dashboard sirkulasi | Phase 1, 4 |
| 6: QA & Deploy | 1 wk | Testing, a11y, perf, deploy VPS/Railway/Forge | All |

---

## Resolved Decisions (2026-11-02)

| Decision | Detail |
|----------|--------|
| Storage | Cloudflare R2 (S3-compatible, zero egress fee, via Laravel Filesystem + league/flysystem-aws-s3-v3) |
| Max pinjam simultan per user | 3 buku |
| Sumber konten | Admin upload only (v1), self-publish v2 (out of scope) |
| Moderasi konten | Tidak perlu (hanya admin upload) |
| Denda | `fine_amount` numeric di borrowings; hitung Rp1.000/hari overdue, tidak ditagih; user diblokir pinjam baru jika >7 hari terlambat |
| Schema buku | Satu tabel `books` dengan `total_copies` + `available_copies`, tanpa `book_copies` |
| Reservation queue | ORDER BY created_at ASC; posisi dihitung on-the-fly via COUNT reserv aktif |

---

## Schema Summary

All schema decisions are resolved (see Resolved Decisions above):
- Single `books` table with `total_copies` + `available_copies` (no `book_copies` relation)
- `fine_amount` numeric on borrowings (logged, not charged)
- Reservation queue ordered by `created_at ASC`, position calculated on-the-fly
- CSV import required columns: `title`, `author`, `category`, `isbn`, `total_copies`, `file_url`, `cover_url`
- **Auth tables**: `users` only (with `role`, `google_id`, `avatar` fields). No `accounts`, `sessions`, or `verification_tokens` tables — Laravel Breeze + Socialite handle auth natively.
- Schema DB sama dengan versi Next.js (PostgreSQL), migration bisa dipakai ulang.

---

## Commands (Expected)

```bash
# Setup (after Phase 0)
composer install
npm install

# Dev server
php artisan serve            # Laravel dev server (http://localhost:8000)
npm run dev                  # Vite watch (asset bundling)

# Database
php artisan migrate          # run migrations
php artisan migrate:fresh    # drop + re-run (dev)
php artisan db:seed          # seed data
php artisan migrate:rollback # rollback last migration

# Make commands
php artisan make:model ModelName          # buat model
php artisan make:livewire ComponentName   # buat Livewire component
php artisan make:filament-resource        # buat Filament resource (admin CRUD)
php artisan make:migration CreateTableName
php artisan make:seeder SeederName
php artisan make:mail MailName
php artisan make:notification NotificationName
php artisan make:rule RuleName

# Cron / Scheduler
php artisan schedule:work    # jalankan scheduler secara manual (dev)

# Testing
./vendor/bin/pest            # run Pest tests
./vendor/bin/pest --filter=TestName  # run specific test

# Maintenance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Quality gates order**: `./vendor/bin/pest` → `php artisan optimize` → manual review.

---

## Conventions from PRD_LARAVEL

- **Dark mode**: Only in reader (PST-306), not global
- **RBAC**: Two roles only — `member` | `admin` (PST-105). Store in `users.role` column.
- **Loan duration**: 7 hari default, extend 1× (PST-404)
- **Progress tracking**: Simpan via Livewire + debounce 2s, atau AJAX endpoint ringan (PST-304)
- **Email templates**: H-1, due today, overdue, reserved available, extended (PST-407) via Laravel Mail + Resend
- **Accessibility**: WCAG 2.1 AA target (PST-602)
- **ORM**: Gunakan Eloquent ORM, bukan query builder mentah (kecuali butuh performa tinggi)
- **Halaman interaktif**: Gunakan Livewire (katalog, reader controls, tombol pinjam)
- **Panel admin**: Gunakan Filament untuk semua CRUD admin (buku, user, laporan) — jangan bikin UI admin manual
- **Layout dasar**: Blade untuk layout dasar & halaman statis
- **Theme**: Warm Neutral diimplementasi via Tailwind config

---

## Out of Scope (v1) — Do Not Implement

Native mobile app, social features (review/forum/following), paid fines/payments, AI recommendation, multi-language, multi-tenant.

---

## Files to Create First (Phase 1)

```
app/
  Models/
    User.php              # dengan role, google_id, avatar
    Book.php
    Category.php
    Borrowing.php
    Reservation.php
    ReadingProgress.php
    Bookmark.php
  Livewire/
    Katalog.php           # katalog halaman
    Reader.php            # reader kontrol
    Bookshelf.php         # bookshelf
    Pinjam.php            # tombol pinjam/kembali
  Filament/
    Resources/
      BookResource.php    # admin CRUD buku
      UserResource.php    # admin manajemen user
database/
  migrations/
    create_users_table.php
    create_books_table.php
    create_categories_table.php
    create_borrowings_table.php
    create_reservations_table.php
    create_reading_progress_table.php
    create_bookmarks_table.php
  seeders/
    DatabaseSeeder.php
resources/
  views/
    layouts/
      app.blade.php       # layout dasar
    welcome.blade.php
    reader.blade.php      # halaman reader PDF/EPUB
routes/
  web.php                 # route definisi
config/
  filesystems.php         # R2 disk setup
tailwind.config.js
vite.config.js
.env.example
```

---

## Risk Areas (High)

1. **Filament 3 kompatibilitas dengan Laravel 11** — verifikasi versi sebelum install
2. **Livewire hydration di reader** — state management halaman reader (current page, progress) harus konsisten antara render server dan client
3. **R2 adapter** — butuh `league/flysystem-aws-s3-v3`, konfigurasi disk `r2` di `config/filesystems.php`
4. **PDF.js/epub.js dengan Vite** — asset bundling untuk reader, worker config untuk PDF.js
5. **Reader dark mode toggle** — Blade + Livewire state sinkronisasi
