# PRD — Pustaka

> Aplikasi perpustakaan digital berbasis web untuk siapa saja.

**Versi:** 1.0
**Tanggal:** 2 November 2026
**Status:** Approved — siap eksekusi
**Tech Stack:** Next.js 14 (App Router) + TypeScript + Tailwind + shadcn/ui + PostgreSQL (Neon) + Drizzle ORM + Auth.js + Supabase Storage/R2 + PDF.js + epub.js + Resend + Vercel

---

## 1. Ringkasan

Pustaka adalah aplikasi perpustakaan digital berbasis web yang memungkinkan siapa saja menemukan, membaca, dan meminjam buku digital (e-book) serta mengelola koleksi bacaan pribadi. Menggabungkan katalog perpustakaan modern dengan pengalaman membaca nyaman di browser, plus panel admin untuk pengelolaan koleksi dan sirkulasi.

**Target user:** Masyarakat umum, pelajar, mahasiswa, pengelola perpustakaan kecil/menengah.

---

## 2. Problem Statement

- Pengguna kesulitan menemukan buku digital legal & mudah diakses dalam satu platform rapi.
- Perpustakaan kecil belum punya sistem digital lengkap untuk koleksi, peminjaman, anggota.
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

### EPIC 1 — Foundation & Auth
| ID | Judul | Priority | Est. | Labels |
|---|---|---|---|---|
| PST-101 | Setup Next.js + TS + Tailwind | P0 | 3 | setup |
| PST-102 | Setup PostgreSQL + Drizzle + migration | P0 | 3 | backend,db |
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
| PST-601 | Setup Vitest + Playwright | P1 | 3 | qa |
| PST-602 | Accessibility audit WCAG 2.1 AA | P1 | 5 | qa,a11y |
| PST-603 | Performance audit Lighthouse | P1 | 3 | qa,perf |
| PST-604 | Deploy Vercel + Neon + R2 | P0 | 3 | devops |
| PST-605 | Dokumentasi README | P2 | 2 | docs |

**Total: 42 stories, 165 story points (~7–8 sprint untuk 1–2 dev).**

---

## 10. Timeline (12 Minggu)

| Fase | Durasi | Deliverable |
|---|---|---|
| Setup & Auth | 2 minggu | Project, DB, auth, role |
| Katalog & Detail | 2 minggu | CRUD admin, katalog, search, detail |
| Reader & Bookshelf | 3 minggu | PDF.js/epub.js, progress, bookmark, bookshelf |
| Sirkulasi | 2 minggu | Pinjam, kembali, reservasi, notifikasi |
| Admin Dashboard | 2 minggu | Manajemen user, laporan, dashboard |
| Testing & Deploy | 1 minggu | QA, a11y, perf, deploy |

---

## 11. Open Questions

| Pertanyaan | Owner | Target |
|---|---|---|
| Perlu fitur denda (tanpa pembayaran)? | Product | Sebelum Fase 4 |
| Max buku dipinjam bersamaan per user? (usul: 3) | Product | Sebelum Fase 4 |
| Sumber konten: upload admin saja atau self-publish? | Product | Sebelum Fase 1 |
| Perlu moderasi konten? | Product | Sebelum Fase 2 |

---

## 12. Out of Scope (v1)

Native mobile app, fitur sosial (review/forum/following), pembayaran/denda berbayar, AI recommendation, multi-bahasa, multi-tenant.