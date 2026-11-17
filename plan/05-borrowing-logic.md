# 05 — Logika Peminjaman, Pengembalian & Denda

> **Status:** ✅ Done (2026-11-13) — Inti sirkulasi selesai: pinjam (stok, anti-duplikat, max 3, blokir keterlambatan >7 hari), kembali, denda Rp1.000/hari, tombol detail, dan halaman "Buku Saya". Extend, antrian reservasi, cron auto-return, dan email **ditunda** (tidak diminta pada iterasi ini).

## Tujuan
Mengimplementasikan alur pinjam buku, pengembalian, perpanjangan (extend), serta perhitungan denda dan auto-return untuk pinjaman yang melewati due date.

## Prasyarat
- `03-user-auth.md` (Done) — aksi pinjam butuh user login.
- `02-book-detail.md` (Done) — tombol pinjam ada di detail buku.
- Model `Borrowing`, `Reservation`, `Book` — ✅ sudah ada.
- Skema `borrowings` (`borrowed_at`, `due_at`, `returned_at`, `extended`, `fine_amount`) & `reservations` — ✅ sudah ada.

## File yang Disentuh
- `app/Services/BorrowingService.php` (buat) — logika pinjam/kembali/extend/denda.
- `app/Http/Controllers/BorrowingController.php` (buat) — endpoint aksi.
- `app/Livewire/BorrowButton.php` (edit) — panggil `BorrowingService`.
- `routes/web.php` (edit) — route pinjam / kembali / extend.
- `app/Models/Book.php` (edit) — method `borrow()` / `returnCopy()` (dalam transaksi).
- `app/Console/Commands/AutoReturnOverdue.php` (buat) — auto-return & hitung denda.
- `routes/console.php` atau `bootstrap/app.php` (edit) — jadwalkan command harian.
- `app/Mail/BorrowingDueReminder.php` (buat) — email H-1 & overdue.
- `resources/views/emails/borrowing-reminder.blade.php` (buat) — template email.

## Langkah Eksekusi
- [x] `BorrowingService@borrow`: cek stok (`available_copies > 0`), cek duplikat (belum pinjam judul sama), cek max 3 pinjaman aktif, blokir bila keterlambatan > 7 hari, `available_copies -= 1` (dalam DB transaction + `lockForUpdate`).
- [x] `BorrowingService@return`: set `returned_at`, `available_copies += 1`, hitung denda.
- [ ] (Ditunda) `BorrowingService@extend` — perpanjang 1x (tidak diminta iterasi ini).
- [x] `BorrowingService@calculateFine`: Rp 1.000 × hari keterlambatan (ceil, min 1 hari).
- [x] `BorrowingController` (bookshelf + borrow + returnBook) & route `/pinjam/{book}`, `/kembalikan/{borrowing}`, `/buku-saya`.
- [x] Hubungkan tombol "Pinjam Buku" di `books/show.blade.php` (form POST + gating login/duplikat/stok).
- [x] Halaman "Buku Saya" (`bookshelf/index.blade.php`): pinjaman aktif + tombol Kembalikan + riwayat + denda.
- [x] Flash message (success/error) di layout.
- [ ] (Ditunda) `AutoReturnOverdue` command + scheduler (tidak diminta iterasi ini).
- [ ] (Ditunda) Email H-1 & overdue via Mailable + Resend (tidak diminta iterasi ini).
- [x] Test: borrow (stok 7→6), duplikat ditolak, return (stok balik 7), denda 2.5 hari → Rp3.000, alur HTTP POST pinjam/kembali + flash.

## Acceptance Criteria
- User bisa pinjam jika stok tersedia & belum pinjam judul sama; `available_copies` berkurang 1.
- User tidak bisa meminjam lebih dari 3 buku simultan.
- Kembali → `returned_at` terisi, stok bertambah, denda terhitung benar (Rp1.000/hari).
- Extend hanya bisa 1x; `due_at` bertambah 7 hari.
- User dengan keterlambatan > 7 hari diblokir dari pinjaman baru.
- Scheduler auto-return berjalan (uji dengan `php artisan schedule:work`).
- Email H-1 & overdue terkirim (uji dengan `MAIL_MAILER=log`).