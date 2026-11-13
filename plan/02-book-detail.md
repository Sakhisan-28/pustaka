# 02 — Halaman Detail Buku + Tombol Pinjam

> **Status:** ✅ Done (2026-11-13) — UI & route selesai. Tombol Pinjam/Reservasi/Baca sudah tampil sesuai status namun **belum fungsional** (dihubungkan di task 03 & 05).

## Tujuan
Menampilkan detail buku (sinopsis, metadata, status ketersediaan) sekaligus tombol aksi pinjam/reservasi yang ter-gating sesuai status login dan stok.

## Prasyarat
- `01-public-catalog.md` (Done) — kartu buku sudah nge-link ke detail.
- Model `Book` (+ relasi `category`, `borrowings`) — ✅ sudah ada.
- Tombol pinjam **fungsional** butuh `03-user-auth.md` (gating login) & `05-borrowing-logic.md` (aksi). Pada task ini fokus ke **UI + gating**.

## File yang Disentuh
- `routes/web.php` (edit) — route `GET /buku/{book}`.
- `app/Http/Controllers/BookController.php` (edit) — method `show()`.
- `resources/views/books/show.blade.php` (buat) — halaman detail.
- `app/Livewire/BorrowButton.php` (buat) — tombol pinjam (Livewire) + gating auth.
- `resources/views/livewire/borrow-button.blade.php` (buat) — view tombol.
- `app/Models/Book.php` (edit) — helper `isAvailable()` / accessor status.

## Langkah Eksekusi
- [x] Tambah `show()` di `BookController` (eager load `category`, `borrowings`).
- [x] Buat `books/show.blade.php`: cover besar, judul, penulis, kategori, ISBN, sinopsis, jumlah copy, status (Tersedia / Stok Habis).
- [x] Tombol aksi (Pinjam / Baca / Reservasi) ditampilkan sesuai status ketersediaan — **UI saja, belum fungsional** (sesuai instruksi).
- [x] Helper `isAvailable()` + accessor `status_label` di model `Book`.
- [x] Metadata & status ketersediaan akurat.
- [x] Tambah route detail buku (`GET /buku/{book}`).
- [x] Test: buka detail dari katalog, pastikan data tampil benar (semua id 200, id tidak ada 404).
- [ ] (Ditunda) Livewire `BorrowButton` + gating login — dikerjakan pada task `03-user-auth` & `05-borrowing-logic`.

## Acceptance Criteria
- Klik kartu buku → halaman detail terbuka dengan data benar.
- Sinopsis + metadata (penulis, kategori, ISBN, jumlah copy) tampil.
- Status ketersediaan akurat (Tersedia / Stok Habis).
- Tombol pinjam/reservasi muncul sesuai status; tamu (belum login) diarahkan ke halaman login.
- Tombol "Pinjam" siap dihubungkan ke logic di task `05-borrowing-logic.md`.