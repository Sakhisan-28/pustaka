# 01 — Katalog Buku Publik

> **Status:** ✅ Done (2026-11-13)

## Tujuan
Menampilkan katalog buku publik berupa grid cover + judul + penulis, dilengkapi search, filter kategori/ketersediaan, dan sorting.

## Prasyarat
- Migrasi database sudah jalan (tabel `books`, `categories`) — ✅ sudah ada.
- Model `Book` & `Category` — ✅ sudah ada.
- (Disarankan) Tema "Warm Neutral" di `tailwind.config.js` sudah diset.

## File yang Disentuh
- `routes/web.php` (edit) — tambah route katalog.
- `app/Http/Controllers/BookController.php` (buat) — method `index()`.
- `resources/views/layouts/app.blade.php` (buat) — layout dasar publik (header, footer, kontainer).
- `resources/views/books/index.blade.php` (buat) — halaman katalog.
- `resources/views/components/book-card.blade.php` (buat) — kartu buku reusable.
- `app/Models/Book.php` (edit) — scope `scopeFilter()` untuk search/filter/sort.
- `tailwind.config.js` (edit, opsional) — token warna Warm Neutral + font Lora/Inter.

## Langkah Eksekusi
- [x] Buat layout dasar `layouts/app.blade.php` (Tailwind, font Inter/Lora, warna Warm Neutral `#FAF8F5` / `#1F2937`).
- [x] Buat `BookController@index` dengan query: search (title/author/isbn/kategori), filter kategori & ketersediaan, sort (terbaru/terpopuler/A-Z), paginasi.
- [x] Buat `book-card.blade.php` (cover, judul, penulis, badge ketersediaan) yang nge-link ke halaman detail.
- [x] Buat `books/index.blade.php`: grid responsif + search bar + filter kategori + dropdown sort.
- [x] Tambah route `GET /katalog` (atau jadikan `/` sebagai katalog) → `BookController@index`.
- [x] Pastikan ada data buku untuk testing (seed data).
- [x] Jalankan `php artisan optimize:clear` dan cek di browser.

## Acceptance Criteria
- Buka `/katalog` → tampil grid buku dengan cover, judul, penulis.
- Search "judul/penulis/ISBN" memfilter hasil dengan benar.
- Filter kategori & "hanya tersedia" berfungsi.
- Sort (terbaru / A-Z) mengubah urutan hasil.
- Paginasi berjalan; waktu muat katalog < 2 detik.
- Responsif: grid 1 kolom di mobile, multi kolom di desktop.