# 04 — Reader PDF / EPUB di Browser

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
- [ ] `npm install pdfjs-dist epubjs`.
- [ ] Buat `ReaderController@show`: validasi akses (login + diizinkan membaca), lewarkan URL file.
- [ ] Buat `reader/index.blade.php`: container reader + toolbar (font size, line height, dark/light toggle).
- [ ] Implement PDF.js viewer (render canvas, navigasi halaman, zoom).
- [ ] Implement epub.js viewer (reflow, navigasi).
- [ ] Deteksi tipe file (PDF vs EPUB) lalu pilih viewer yang sesuai.
- [ ] Simpan progress otomatis (debounce 2s) via `ReadingProgressController` saat halaman berubah.
- [ ] Load progress terakhir saat reader dibuka (lanjut dari halaman terakhir).
- [ ] Tombol kembali ke halaman detail / breadcrumb.

## Acceptance Criteria
- Buka `/baca/{book}` untuk file PDF → ter-render via PDF.js.
- Buka untuk file EPUB → ter-render via epub.js.
- Navigasi halaman (klik / keyboard) berfungsi.
- Font size & dark mode toggle berfungsi.
- Progress tersimpan otomatis; buka lagi → lanjut dari halaman terakhir.
- Tidak ada error di console; render < 1.5 detik/halaman.