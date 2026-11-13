# 05 — Logika Peminjaman, Pengembalian & Denda

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
- [ ] `BorrowingService@borrow`: cek user belum punya pinjaman aktif untuk judul sama, cek `available_copies > 0`, cek max 3 pinjaman simultan, buat record (`borrowed_at = now`, `due_at = +7 hari`), `available_copies -= 1` — semua dalam DB transaction.
- [ ] `BorrowingService@return`: set `returned_at`, `available_copies += 1`, hitung denda (Rp1.000/hari overdue), picu reservasi berikutnya (jika ada).
- [ ] `BorrowingService@extend`: hanya 1x per pinjaman, `due_at += 7 hari`, set `extended = true`.
- [ ] `BorrowingService@calculateFine`: `Rp1.000 × jumlah hari keterlambatan`.
- [ ] Blokir pinjaman baru jika user punya keterlambatan > 7 hari.
- [ ] Buat `AutoReturnOverdue` command: auto-return yang lewat due, set denda, jalankan via scheduler harian.
- [ ] Buat email H-1 & overdue via Mailable + Resend.
- [ ] Hubungkan tombol pinjam (task 02) ke `BorrowingService`.
- [ ] Test: pinjam → stok berkurang; kembali → stok bertambah; extend sekali; denda terhitung benar.

## Acceptance Criteria
- User bisa pinjam jika stok tersedia & belum pinjam judul sama; `available_copies` berkurang 1.
- User tidak bisa meminjam lebih dari 3 buku simultan.
- Kembali → `returned_at` terisi, stok bertambah, denda terhitung benar (Rp1.000/hari).
- Extend hanya bisa 1x; `due_at` bertambah 7 hari.
- User dengan keterlambatan > 7 hari diblokir dari pinjaman baru.
- Scheduler auto-return berjalan (uji dengan `php artisan schedule:work`).
- Email H-1 & overdue terkirim (uji dengan `MAIL_MAILER=log`).