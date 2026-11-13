# 04 — Reader PDF / EPUB di Browser

> **Status:** ✅ Done (2026-11-13) — Reader PDF & EPUB, toolbar (font size, dark mode), navigasi, dan simpan progress (debounce 2s) selesai. Verifikasi sisi server: halaman + endpoint file 200 dengan content-type benar. Rendering aktual PDF/EPUB perlu dicek di browser.

## Tujuan
Menyediakan halaman baca in-browser untuk file PDF & EPUB dengan navigasi halaman, kontrol tampilan (font size, dark mode), dan penyimpanan progress otomatis.

## Prasyarat
- `02-book-detail.md` (Done) — ada link "Baca" dari detail buku.
- `03-user-auth.md` (Done) — akses baca ter-gating login.
- File buku tersimpan di disk (saat ini `storage/app/books`) — ✅.
- Model `ReadingProgress` & `Bookmark` — ✅ sudah ada.

## File yang Disentuh
- `routes/web.php` (edit) — route `GET /baca/{book}` + endpoint simpan progress.
- `app/Http/Controllers/ReaderController.php` (buat) — validasi akses + kirim URL file.
- `app/Http/Controllers/ReadingProgressController.php` (buat) — simpan/load progress (AJAX).
- `resources/views/reader/index.blade.php` (buat) — halaman reader + toolbar.
- `resources/js/reader/pdf-viewer.js` (buat) — integrasi PDF.js.
- `resources/js/reader/epub-viewer.js` (buat) — integrasi epub.js.
- `resources/js/app.js` (edit) — import viewer.
- `package.json` (edit) — tambah `pdfjs-dist` & `epubjs`.

## Langkah Eksekusi
- [x] `npm install pdfjs-dist epubjs`.
- [x] Buat `ReaderController@show`: validasi akses (login + file tersedia), deteksi tipe, lewarkan URL file + progress awal.
- [x] Buat `reader/index.blade.php`: container reader + toolbar (font size, dark/light toggle, indikator halaman) + navigasi prev/next.
- [x] Implement PDF.js viewer (`pdf-viewer.js` — render canvas, navigasi halaman, invert untuk dark mode).
- [x] Implement epub.js viewer (`epub-viewer.js` — render, navigasi, tema dark/light, font size).
- [x] Deteksi tipe file (PDF vs EPUB) lalu pilih viewer yang sesuai.
- [x] Simpan progress otomatis (debounce 2s) via `ReadingProgressController` (POST `/baca/{book}/progress`).
- [x] Load progress terakhir saat reader dibuka (lanjut dari halaman/halaman terakhir).
- [x] Tombol kembali ke halaman detail + endpoint file ter-autentikasi (`/baca/{book}/file`).
- [x] Code-split: reader (PDF.js/epub.js) hanya dimuat di halaman reader (dynamic import di `app.js`).

## Acceptance Criteria
- Buka `/baca/{book}` untuk file PDF → ter-render via PDF.js.
- Buka untuk file EPUB → ter-render via epub.js.
- Navigasi halaman (klik / keyboard) berfungsi.
- Font size & dark mode toggle berfungsi.
- Progress tersimpan otomatis; buka lagi → lanjut dari halaman terakhir.
- Tidak ada error di console; render < 1.5 detik/halaman.