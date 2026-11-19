# 07 — Bulk Update Covers

> **Status:** ✅ Done (2026-11-18) — Command Artisan untuk mengisi `cover_url` buku yang masih kosong memakai Open Library.

## Tujuan
Merapikan data buku yang belum memiliki sampul dengan mengambil URL cover dari Open Library berdasarkan ISBN.

## Prasyarat
- `PST-207` (Done) — CRUD buku tersedia.
- Model `Book` dan field `isbn`, `cover_url` — ✅ sudah ada.
- Koneksi internet dari server untuk mengakses `https://covers.openlibrary.org`.
- Tidak memerlukan API key.

## File yang Disentuh
- `plan/07-bulk-update-covers.md` (buat) — dokumen rencana ini.
- `app/Console/Commands/UpdateBookCovers.php` (buat) — command Artisan.
- `plan/README.md` (edit) — daftar task dan status.

## Langkah Eksekusi
- [x] Buat command `UpdateBookCovers` dengan signature `books:update-covers`.
- [x] Ambil buku dengan `cover_url` kosong/null.
- [x] Lewati buku tanpa ISBN.
- [x] Susun URL `https://covers.openlibrary.org/b/isbn/{isbn}-L.jpg`.
- [x] Cek URL cover dengan HTTP HEAD sebelum menyimpan.
- [x] Update `cover_url` hanya untuk buku yang cover-nya tersedia.
- [x] Tampilkan progress per buku dan ringkasan di akhir.
- [x] Daftarkan command agar ditemukan otomatis oleh Laravel.

## Acceptance Criteria
- `php artisan books:update-covers` dapat dijalankan.
- Hanya buku dengan `cover_url` kosong/null yang diproses.
- Buku tanpa ISBN dilewati tanpa error.
- URL cover yang tidak tersedia tidak disimpan.
- Terminal menampilkan "Updated cover for book: [Judul]" untuk setiap buku yang diperbarui.
- Command selesai dengan ringkasan jumlah diperbarui dan dilewati.