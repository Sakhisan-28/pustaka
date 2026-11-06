# PRD Laravel — Pustaka

> Aplikasi perpustakaan digital berbasis web berbasis Laravel untuk siapa saja.

**Versi:** 1.0  
**Tanggal:** 2 November 2026  
**Status:** Approved — siap eksekusi  
**Tech Stack:** Laravel 11 + Livewire 3 + Filament 3 + Tailwind CSS + PostgreSQL (Neon) + Eloquent ORM + Laravel Breeze + Laravel Socialite + Cloudflare R2 (S3-compatible) via @aws-sdk/client-s3 + PDF.js + epub.js + Resend + VPS/Railway/Forge Deploy

---

## 1. Ringkasan

Pustaka adalah aplikasi perpustakaan digital berbasis web yang memungkinkan siapa saja menemukan, membaca, dan meminjam buku digital (e-book) serta mengelola koleksi bacaan pribadi. Menggabungkan katalog perpustakaan modern dengan pengalaman membaca nyaman di browser, plus panel admin untuk pengelolaan koleksi dan sirkulasi.

**Target user:** Masyarakat umum, pelajar, mahasiswa, pengelola perpustakaan kecil/menengah.

---

## 2. Problem Statement

- Pengguna kesulitan menemukan buku digital legal & mudah diakses dalam satu platform rapi.
- Perpustakaya kecil belum punya sistem digital lengkap untuk koleksi, peminjaman, anggota.
- Pengalaman baca e-book di browser sering tidak nyaman (navigasi buruk, tanpa progress tracking, tidak responsif).

---

## 3. Goals & Success Metrics

**Goals:**
1. Katalog buku digital yang cepat dicari & difilter.
2. Pengalaman baca e-book nyaman langsung di browser.
3. Sistem peminjaman digital dengan tracking status & due date.
4. Panel admin untuk manajemen koleksi, anggota, laporan.

**Success Metrics (90 hari):**

| Metrik | Target |
|---|---|
| User terdaftar | 500 |
| Buku dalam katalog | 200+ |
| Sesi baca/user/minggu | ≥ 3 |
| Completion rate peminjaman tepat waktu | ≥ 80% |
| Waktu muat katalog | < 2 detik |

---

## 4. Persona

**Rina, 24 — Pembaca Umum:** cari buku cepat, baca di HP/laptop, simpan progress.
**Pak Budi, 40 — Pengelola Perpustakaan:** upload buku, kelola anggota, lihat laporan.
**Dewi, 17 — Anggota Terdaftar:** pinjam buku digital, baca langsung, notifikasi jatuh tempo.

---

## 5. Scope

**In Scope (Full):** auth (email + Google OAuth), katalog + search/filter/sort, detail buku, reader PDF/EPUB in-browser, peminjaman digital + reservasi + due date, bookshelf pribadi, notifikasi email, panel admin lengkap, RBAC (member/admin).

**Out of Scope (v1):** native mobile app, fitur sosial (review/forum/following), pembayaran/denda otomatis, AI recommendation, multi-bahasa, multi-tenant.

---

## 6. Tema Visual — "Pustaka Modern: Warm Neutral"

| Elemen | Nilai |
|---|---|
| Background utama | `#FAF8F5` |
| Surface/card | `#FFFFFF` / `#F3EFE9` |
| Teks utama | `#1F2937` |
| Teks sekunder | `#6B7280` |
| Aksen utama | `#3B82F6` |
| Aksen sekunder | `#D97706` |
| Border | `#E5E1DA` |
| Font judul | Lora (serif) |
| Font UI | Inter (sans-serif) |
| Radius | 8–12px |
| Mode | Light default + Dark toggle di reader |

**Kesan:** hangat, nyaman dibaca lama, modern tapi tidak dingin.

---

## 7. Functional Requirements (P0)

### 7.1 Auth
- Register email + password
- Login via Google OAuth
- Reset password via email
- Edit profil (nama, foto, preferensi)

### 7.2 Katalog
- Grid/list buku (cover, judul, penulis)
- Search judul/penulis/ISBN/kategori
- Filter kategori, ketersediaan, tahun, bahasa
- Sort (terbaru, terpopuler, A-Z)
- Detail buku: sinopsis, metadata, status, preview

### 7.3 Reader
- PDF & EPUB support
- Navigasi halaman (klik, swipe, keyboard)
- Font size, line height, dark/light mode
- Progress bacaan otomatis
- Bookmark halaman

### 7.4 Sirkulasi
- Pinjam jika stok tersedia (1 user = 1 copy aktif/judul)
- Durasi default 7 hari, extend 1x
- Antrian reservasi
- Return manual / auto saat due date
- Notifikasi email H-1

### 7.5 Bookshelf
- Tabs: Dipinjam / Riwayat / Favorit
- Progress bar per buku
- Tombol "Lanjutkan Baca"

### 7.6 Admin
- CRUD buku (upload PDF/EPUB + cover)
- Bulk import CSV
- Manajemen user (aktif/nonaktif, ubah role)
- Dashboard sirkulasi
- Laporan + export CSV

---

## 8. Non-Functional Requirements

| Kategori | Requirement |
|---|---|
| Performance | Katalog < 2s; reader render < 1.5s/halaman |
| Scalability | 1.000 concurrent users |
| Security | bcrypt, HTTPS, RBAC, rate limiting auth |
| Compatibility | Chrome/Firefox/Safari/Edge 2 versi terakhir; mobile browser |
| Availability | 99.5% uptime |
| Accessibility | WCAG 2.1 AA |

---

## 9. Task Breakdown (Jira-Ready)

### EPIC 1 — Foundation & Auth (Laravel)

| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-101 | Setup Laravel 11 + Tailwind + Livewire | P0 | 3 | setup |
| PST-102 | Setup PostgreSQL + Laravel Eloquent + migration | P0 | 3 | backend,db |
| PST-103 | Auth register + login email/password | P0 | 5 | backend,auth |
| PST-104 | OAuth Google | P1 | 3 | backend,auth |
| PST-105 | RBAC member vs admin | P0 | 2 | backend,auth |
| PST-106 | Reset password via email | P1 | 3 | backend,auth |

### EPIC 2 — Katalog Buku

| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-201 | Schema books, categories, authors | P0 | 3 | backend,db |
| PST-202 | GET /api/books (list, search, filter, pagination) | P0 | 5 | backend |
| PST-203 | GET /api/books/:id | P0 | 2 | backend |
| PST-204 | Halaman katalog grid responsive | P0 | 5 | frontend |
| PST-205 | Search bar + filter + sorting | P0 | 5 | frontend |
| PST-206 | Halaman detail buku | P0 | 5 | frontend |
| PST-207 | Admin CRUD buku + upload file | P0 | 8 | admin,backend |
| PST-208 | Bulk import CSV | P2 | 5 | admin |
| PST-209 | Komponen BookCard reusable | P0 | 2 | frontend |

### EPIC 3 — Reader & Bookshelf

| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-301 | Schema reading_progress + bookmarks | P0 | 2 | backend,db |
| PST-302 | Integrasi PDF.js | P0 | 8 | frontend,reader |
| PST-303 | Integrasi epub.js | P0 | 8 | frontend,reader |
| PST-304 | Simpan/load progress otomatis | P0 | 5 | backend,frontend |
| PST-305 | Bookmark halaman | P1 | 3 | frontend,backend |
| PST-306 | Dark mode + font settings reader | P1 | 3 | frontend |
| PST-307 | Halaman bookshelf | P0 | 5 | frontend |
| PST-308 | Tombol "Lanjutkan Baca" | P0 | 2 | frontend |
| PST-309 | Favorit buku | P1 | 2 | frontend,backend |

### EPIC 4 — Sirkulasi

| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-401 | Schema borrowings + reservations | P0 | 3 | backend,db |
| PST-402 | POST /api/borrowings | P0 | 5 | backend |
| PST-403 | PATCH /api/borrowings/:id/return | P0 | 3 | backend |
| PST-404 | Extend pinjam 1x | P1 | 3 | backend |
| PST-405 | Antrian reservasi | P1 | 8 | backend |
| PST-406 | Cron auto-return overdue | P0 | 3 | backend,cron |
| PST-407 | Notifikasi email | P0 | 5 | backend,email |
| PST-408 | UI tombol Pinjam/Reservasi/Kembali | P0 | 3 | frontend |

### EPIC 5 — Admin & Laporan

| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-501 | Layout admin | P0 | 3 | admin,frontend |
| PST-502 | Manajemen user | P0 | 5 | admin |
| PST-503 | Dashboard sirkulasi | P0 | 3 | admin |
| PST-504 | Laporan pinjaman + export CSV | P1 | 5 | admin |
| PST-505 | Laporan buku terpopuler | P2 | 3 | admin |

### EPIC 6 — QA & Deploy

| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-601 | Setup Pest PHP | P1 | 3 | qa |
| PST-602 | Accessibility audit WCAG 2.1 AA | P1 | 5 | qa,a11y |
| PST-603 | Performance audit | P1 | 3 | qa,perf |
| PST-604 | Deploy ke VPS/Railway/Forge | P0 | 3 | devops |
| PST-605 | Dokumentasi README | P2 | 2 | docs |

**Total: 42 stories, 165 story points (~9 minggu untuk 1–2 dev).**

---

## 10. Timeline (~9 Minggu)

| Fase | Durasi | Deliverable |
|---|---|---|
| Setup & Auth | 1 minggu | Project, DB, auth, role |
| Katalog & Detail | 1.5 minggu | CRUD admin, katalog, search, detail |
| Reader & Bookshelf | 3 minggu | PDF.js/epub.js, progress, bookmark, bookshelf |
| Sirkulasi | 2 minggu | Pinjam, kembali, reservasi, notifikasi |
| Admin Dashboard | 0.5 minggu | Layout admin, manajemen user, dashboard |
| QA & Deploy | 1 minggu | Testing, a11y, perf, deploy |

---

## 11. Migration Notes (Next.js → Laravel)

**Yang Tetap Sama:**
- Fitur lengkap: auth, katalog, reader, sirkulasi, bookshelf, admin panel
- Tema visual Warm Neutral (Lora + Inter, warna #FAF8F5 dsb.)
- Schema database: satu tabel `books` dengan `total_copies` + `available_copies`, tanpa `book_copies`
- Logic pinjam/kembali: `available_copies -= 1` saat pinjam, `+= 1` saat kembali
- Denda: `fine_amount` numeric di borrowings, hitung Rp1.000/hari overdue, tidak ditagih; user diblokir pinjam baru jika >7 hari terlambat
- Reservasi queue: ORDER BY created_at ASC, posisi dihitung on-the-fly via COUNT reservasi aktif
- CSV import schema: `title`, `author`, `category`, `isbn`, `total_copies`, `file_url`, `cover_url`
- Out of scope: native mobile app, sosial, pembayaran/denda, AI, multi-bahasa, multi-tenant

**Perubahan Utama:**
| Aspek | Next.js (Lama) | Laravel (Baru) |
|---|---|---|
| Framework | Next.js 14 (App Router) | Laravel 11 |
| Frontend | React + TypeScript | Livewire 3 + Blade |
| Panel Admin | shadcn/ui + custom UI | Filament 3 |
| Database ORM | Drizzle ORM | Eloquent (bawaan Laravel) |
| Auth | Auth.js (NextAuth v5) | Laravel Breeze + Socialite |
| File Storage | Cloudflare R2 via @aws-sdk | Laravel Filesystem + R2 adapter |
| PDF/EPUB Reader | PDF.js + epub.js (JS) | PDF.js + epub.js (di-blade component) |
| Cron | Vercel Cron / pg_cron | Laravel Scheduler (protected/) |
| Deploy | Vercel | VPS / Railway / Laravel Forge |
| Testing | Vitest + Playwright | Pest PHP |
| Env Config | .env + drizzle.config.ts | .env + php artisan migrate |

**Catatan Migrasi:**
- Schema DB bisa dipakai ulang karena tetap PostgreSQL dengan struktur tabel yang sama
- File e-book (PDF/EPUB) di R2 bisa dipakai ulang, hanya perlu adapter Laravel
- Notifikasi email format bisa disesuaikan dengan Laravel Mail + Resend templates
- URL dan route struktur berubah (REST API vs App Router Next.js)
- Kontributor pengembangan berubah: Node.js/TS developer ke PHP/Laravel developer

---

## 12. Out of Scope (v1)

Native mobile app, fitur sosial (review/forum/following), pembayaran/denda berbayar, AI recommendation, multi-bahasa, multi-tenant.