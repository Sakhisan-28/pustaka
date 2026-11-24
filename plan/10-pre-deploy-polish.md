# 10 — Pre-Deploy Polish (UI/UX & Performance)

> **Status:** 🚧 In Progress — Polish sebelum deploy: error pages bertema Warm Neutral, navigasi reader yang jelas, cek responsif katalog/detail, dan optimasi ukuran gambar cover (Open Library `-M.jpg`). Siap diuji manual di browser (mode mobile/responsive).

## Tujuan
Merapikan sisi UI/UX dan performa ringan sebelum go-live:
1. Halaman error 404 & 500 yang konsisten dengan tema (krem, font Lora), bukan halaman bawaan Laravel.
2. Tombol "Kembali/Tutup Buku" yang jelas di reader (pojok kiri atas) menuju detail buku.
3. Memastikan grid katalog & tombol aksi detail tidak terpotong di layar HP.
4. Cover dari Open Library memakai ukuran Medium (`-M.jpg`) untuk menghemat bandwidth di katalog (daftar banyak gambar), sementara halaman detail tetap memakai Large (`-L.jpg`) agar cover besar tetap tajam.

## Prasyarat
- `01-public-catalog.md` (Done) — katalog + `book-card`.
- `02-book-detail.md` (Done) — halaman detail + tombol aksi.
- `04-reader-ui.md` (Done) — halaman reader + toolbar.
- Tema Warm Neutral sudah ada di `tailwind.config.js` (`warm-*`, `font-serif` = Lora).

## File yang Disentuh
- `plan/10-pre-deploy-polish.md` (buat) — dokumen rencana ini.
- `plan/README.md` (edit) — daftar task + status.
- `resources/views/errors/404.blade.php` (buat) — halaman 404 bertema.
- `resources/views/errors/500.blade.php` (buat) — halaman 500 bertema.
- `resources/views/reader/index.blade.php` (edit) — tombol "Kembali" lebih jelas.
- `resources/views/books/show.blade.php` (edit) — minta cover Large.
- `app/Models/Book.php` (edit) — `coverUrl()` dukung ukuran cover (default Medium untuk hemat bandwidth).

## Langkah Eksekusi
- [x] Buat `errors/404.blade.php` (standalone, @vite, tema warm + Lora) dengan pesan "Buku atau halaman yang kamu cari tidak ditemukan." + tombol ke Katalog.
- [x] Buat `errors/500.blade.php` (standalone, tema sama) dengan pesan error umum + tombol kembali.
- [x] Reader: ubah link kembali jadi tombol ber-border yang jelas (pojok kiri atas) ke `books.show`.
- [x] Cek katalog: grid `grid-cols-2 sm:grid-cols-3 lg:grid-cols-4` (sudah responsif), form filter stack di mobile — konfirmasi tidak ada yang terpotong.
- [x] Cek detail: tombol aksi `flex-col sm:flex-row` (sudah responsif) — konfirmasi tidak ada yang terpotong.
- [x] `Book::coverUrl($size = 'M')` — turunkan kode ukuran `-L/-M/-S.jpg` pada URL Open Library; default Medium.
- [x] `book-card` pakai default (Medium); `show` minta `coverUrl('L')`.
- [x] `npm run build` + `php artisan optimize:clear`.

## Acceptance Criteria
- Buka URL acak (404) → tampil halaman bertema warm + Lora dengan tombol "Kembali ke Katalog".
- Picu error (500) → tampil halaman bertema dengan tombol kembali, bukan stack trace.
- Di reader ada tombol "← Kembali" yang jelas di pojok kiri atas menuju detail buku.
- Katalog & detail rapi di layar HP (grid 2 kolom, tombol menumpuk vertikal, tidak terpotong).
- Cover di katalog dimuat via Open Library `-M.jpg` (lebih ringan); cover detail tetap `-L.jpg`.

## Catatan
- Error page dibuat standalone (tidak extend `layouts.app`) agar tetap tampil walau layout/header bermasalah — itu justru saat 500 paling sering terjadi.
- `preg_replace` hanya mengubah URL yang berakhiran `-L/-M/-S.jpg` (cover Open Library); URL R2/lokal tidak tersentuh.