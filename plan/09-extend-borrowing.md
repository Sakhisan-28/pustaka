# 09 — Perpanjang Peminjaman (Extend)

> **Status:** 🚧 In Progress — Member dapat memperpanjang pinjaman 1× (tambah 7 hari) sebelum jatuh tempo. Siap diuji manual di browser.

## Tujuan
Member bisa memperpanjang masa pinjam buku 1 kali sebanyak 7 hari, selama buku belum dikembalikan, belum pernah diperpanjang, dan belum melewati tanggal jatuh tempo.

## Prasyarat
- `05-borrowing-logic.md` (Done) — Model `Borrowing` + field `extended` (boolean, default `false`) ✅ sudah ada di migrasi `create_borrowings_table`.
- `05-borrowing-logic.md` (Done) — Halaman "Buku Saya" (`bookshelf/index.blade.php`) ✅ sudah ada.
- `BorrowingService` dengan konstanta `LOAN_DAYS = 7` ✅ sudah ada.

## File yang Disentuh
- `plan/09-extend-borrowing.md` (buat) — dokumen rencana ini.
- `plan/README.md` (edit) — daftar task dan status.
- `app/Services/BorrowingService.php` (edit) — method `extend()` + validasi dalam transaksi.
- `app/Http/Controllers/BorrowingController.php` (edit) — method `extend()`.
- `app/Models/Borrowing.php` (edit) — cast `extended` jadi boolean.
- `routes/web.php` (edit) — route `borrowings.extend`.
- `resources/views/bookshelf/index.blade.php` (edit) — tombol "Perpanjang 1x" + badge "Sudah Diperpanjang".
- `resources/views/emails/due-date-reminder.blade.php` (edit) — teks disesuaikan dengan aturan extend.
- `tests/Feature/BorrowingTest.php` (edit) — test skenario extend.

## Langkah Eksekusi
- [x] `BorrowingService@extend`: kunci baris (`lockForUpdate`) dalam `DB::transaction`, validasi berurutan — sudah dikembalikan, sudah pernah diperpanjang, sudah lewat jatuh tempo.
- [x] `due_at` bertambah `LOAN_DAYS` (7 hari) dari nilai lama, `extended` jadi `true`, simpan.
- [x] `BorrowingController@extend`: cek kepemilikan (`403` bila bukan pinjaman user), panggil service, redirect `back()` dengan flash sukses/gagal.
- [x] Route `POST /buku-saya/{borrowing}/extend` name `borrowings.extend` di dalam grup `auth`.
- [x] Tombol "Perpanjang 1x" (outline kuning `amber`) di bookshelf, hanya tampil bila `!extended` dan `due_at` belum lewat.
- [x] Badge "Sudah Diperpanjang" bila `extended === true`.
- [x] Cast `extended` jadi boolean — tanpa ini `$borrowing->extended` mengembalikan `1`/`0` dan `assertTrue` gagal.
- [x] Teks email H-1: percabangan `extended` (sudah diperpanjang → tidak bisa lagi) vs default (masih bisa 1×).
- [x] Test: extend berhasil (due_at +7, extended true), ditolak bila sudah dikembalikan, ditolak bila sudah pernah diperpanjang, ditolak bila sudah lewat jatuh tempo, ditolak bila bukan milik user (403).
- [x] `npm run build` + `php artisan optimize:clear`.

## Acceptance Criteria
- User bisa menekan "Perpanjang 1x" di halaman Buku Saya dan `due_at` bergeser 7 hari.
- Setelah diperpanjang, tombol hilang dan diganti badge "Sudah Diperpanjang"; percobaan kedua ditolak.
- Buku yang sudah dikembalikan atau sudah lewat jatuh tempo tidak bisa diperpanjang.
- User lain tidak bisa memperpanjang pinjaman orang lain (403).
- Email pengingat jatuh tempo konsisten dengan aturan extend.
- Test terkait lulus (49 passed).

## Catatan
- Tombol perpanjang tidak muncul untuk pinjaman yang sudah lewat jatuh tempo — di kasus itu user harus mengembalikan dan membayar denda, bukan memperpanjang.
- `extended` tidak di-reset saat buku dikembalikan; nilai ini hanya berarti "pinjaman ini pernah diperpanjang".