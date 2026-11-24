# Pustaka — Daftar Rencana Task (Spec-Driven Development)

> Kumpulan task kecil turunan dari `PRD_LARAVEL.md`. Setiap task = 1 file = 1 unit kerja fokus.
> Alur kerja: **baca file task → eksekusi → centang checklist → penuhi Acceptance Criteria → ubah status jadi Done.**

---

## Cara Pakai

1. Pilih satu task dari index di bawah.
2. Buka file task-nya, baca **Tujuan**, **Prasyarat**, **File yang Disentuh**, **Langkah Eksekusi**, dan **Acceptance Criteria**.
3. Kerjakan hanya task tersebut (jangan menyentuh file di luar "File yang Disentuh" kecuali ada alasan kuat).
4. Centang `[ ]` → `[x]` di "Langkah Eksekusi" sambil jalan.
5. Setelah semua Acceptance Criteria terpenuhi, ubah **Status** di tabel index ini menjadi `✅ Done`.

---

## Index Task

| No | File | Judul | Prioritas | Status |
|----|------|-------|-----------|--------|
| 01 | [01-public-catalog.md](./01-public-catalog.md) | Katalog Buku Publik | P0 | ✅ Done |
| 02 | [02-book-detail.md](./02-book-detail.md) | Detail Buku + Tombol Pinjam | P0 | ✅ Done |
| 03 | [03-user-auth.md](./03-user-auth.md) | Auth User (Breeze + Google OAuth) | P0 | ✅ Done |
| 04 | [04-reader-ui.md](./04-reader-ui.md) | Reader PDF / EPUB di Browser | P0 | ✅ Done |
| 05 | [05-borrowing-logic.md](./05-borrowing-logic.md) | Logika Pinjam / Kembali / Denda | P0 | ✅ Done |
| 06 | [06-google-books-api.md](./06-google-books-api.md) | Integrasi Open Library API | P1 | ✅ Done |
| 07 | [07-bulk-update-covers.md](./07-bulk-update-covers.md) | Bulk Update Covers | P1 | ✅ Done |
| 08 | [08-email-notifications.md](./08-email-notifications.md) | Email Notification (Resend) | P1 | 🚧 In Progress |
| 09 | [09-extend-borrowing.md](./09-extend-borrowing.md) | Perpanjang Peminjaman (Extend) | P1 | 🚧 In Progress |
| 10 | [10-pre-deploy-polish.md](./10-pre-deploy-polish.md) | Pre-Deploy Polish (UI/UX & Performance) | P1 | 🚧 In Progress |

> **Catatan urutan:** PRD menomori sesuai fokus fitur, tetapi untuk implementasi disarankan mengerjakan **03 (auth) lebih dulu**, lalu 01 → 02 → 05 → 04, karena tombol pinjam (02) dan reader (04) bergantung pada auth (03) dan logic pinjam (05).

---

## Sudah Selesai (Done)

| ID | Judul | Status |
|----|-------|--------|
| PST-207 | Admin CRUD Buku + upload file (Filament) | ✅ Done |
| PST-502 | Manajemen User via Filament (role, password) | ✅ Done |
| PST-204/205 | Katalog publik (grid, search, filter, sort) | ✅ Done |
| PST-209 | Komponen BookCard reusable | ✅ Done |
| PST-206 | Halaman detail buku + tombol aksi | ✅ Done |
| PST-103 | Auth register + login email/password (Breeze) | ✅ Done |
| PST-105 | RBAC member vs admin (Filament panel gating) | ✅ Done |
| PST-104 | OAuth Google (Socialite) — scaffold, tunggu kredensial | 🚧 Scaffold |
| PST-302/303 | Reader PDF.js & epub.js (UI + navigasi) | ✅ Done |
| PST-304 | Simpan/load progress bacaan otomatis | ✅ Done |
| PST-402 | Pinjam buku (stok, anti-duplikat, max 3, blokir overdue) | ✅ Done |
| PST-403 | Kembali buku + denda | ✅ Done |
| PST-408 | UI tombol Pinjam + halaman Buku Saya (bookshelf) | ✅ Done |
| PST-404/405/406/407 | Extend, reservasi, cron auto-return, email | ⏳ Deferred |
| PST-404 | Perpanjang pinjaman 1× (7 hari) + tombol di Buku Saya | 🚧 In Progress |
| PST-407 (partial) | Email pengingat jatuh tempo H-1 via Resend + command terjadwal | 🚧 In Progress |
| — | Lookup metadata buku via Open Library API (Filament) | ✅ Done |
| — | Bulk update cover buku via Artisan command | ✅ Done |
| — | Fix schema `category_id` nullable + storage lokal | ✅ Done |

---

## Legenda Status

- ⏳ **Pending** — belum dikerjakan.
- 🚧 **In Progress** — sedang dikerjakan.
- ✅ **Done** — selesai & lolos Acceptance Criteria.