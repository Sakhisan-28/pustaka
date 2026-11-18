# 06 — Integrasi Open Library API

> **Status:** ✅ Done (2026-11-18) — Tombol "Cari via ISBN" di form buku admin menggunakan Open Library API sebagai pengganti Google Books yang terkena rate limit/quota.

## Tujuan
Mempermudah admin menambahkan buku dengan mengambil metadata dari Open Library API berdasarkan ISBN, meliputi judul, penulis, deskripsi, dan URL sampul.

## Prasyarat
- `PST-207` (Done) — CRUD buku di panel admin sudah tersedia.
- Model `Book` dan field `isbn`, `title`, `author`, `description`, `cover_url` — ✅ sudah ada.
- Koneksi internet dari server untuk mengakses `https://openlibrary.org`.
- Tidak memerlukan API key.

## File yang Disentuh
- `plan/06-google-books-api.md` (edit) — dokumen rencana ini diperbarui ke Open Library.
- `app/Services/OpenLibraryService.php` (buat) — wrapper pemanggilan Open Library API.
- `app/Filament/Resources/BookResource.php` (edit) — tombol "Cari via ISBN" pada field ISBN.
- `app/Models/Book.php` (edit) — helper `coverUrl()` untuk mendukung URL sampul eksternal.
- `resources/views/books/show.blade.php` (edit) — tampilkan sampul dari URL eksternal.
- `resources/views/components/book-card.blade.php` (edit) — tampilkan sampul dari URL eksternal.
- `tests/Feature/OpenLibraryServiceTest.php` (buat) — test parsing respons API.
- `tests/Feature/OpenLibraryLookupActionTest.php` (buat) — test action Filament mengisi form.

## Langkah Eksekusi
- [x] Buat `OpenLibraryService@findByIsbn` yang memanggil `GET https://openlibrary.org/isbn/{isbn}.json`.
- [x] Parse `title`, `authors`, dan `description` dari respons Open Library.
- [x] Ambil nama penulis dari `https://openlibrary.org/authors/{id}.json` jika tersedia.
- [x] Set `cover_url` ke `https://covers.openlibrary.org/b/isbn/{isbn}-L.jpg`.
- [x] Tambahkan action "Cari via ISBN" pada field ISBN di `BookResource`.
- [x] Action mengisi `title`, `author`, `description`, dan `cover_url` jika data ditemukan.
- [x] Tampilkan notifikasi sukses, warning (tidak ditemukan/ISBN kosong), atau danger (gagal koneksi).
- [x] Dukung `cover_url` berupa URL eksternal pada form, tabel admin, dan halaman katalog/detail.
- [x] Test service dengan `Http::fake` untuk kasus ditemukan dan tidak ditemukan.
- [x] Test action Filament mengisi field form dari respons Open Library.

## Acceptance Criteria
- Admin dapat mengetik ISBN lalu menekan "Cari via ISBN" pada form buku.
- Jika Open Library mengembalikan data, field `title`, `author`, `description`, dan `cover_url` terisi otomatis.
- Jika ISBN kosong, tidak ditemukan, atau API gagal, admin menerima notifikasi yang jelas tanpa error fatal.
- URL sampul dari Open Library dapat ditampilkan di tabel admin, kartu katalog, dan halaman detail buku.
- Test terkait lulus.