# 08 — Email Notification (Resend)

> **Status:** 🚧 In Progress — Integrasi Resend + Mailable pengingat jatuh tempo (H-1) selesai; pengiriman email nyata belum diverifikasi karena `onboarding@resend.dev` hanya untuk testing.

## Tujuan
Mengirim email pengingat otomatis kepada member bahwa buku yang dipinjamnya jatuh tempo besok (H-1), menggunakan Resend sebagai transport mail Laravel.

## Prasyarat
- `05-borrowing-logic.md` (Done) — Model `Borrowing` (`user_id`, `book_id`, `borrowed_at`, `due_at`, `returned_at`, `extended`, `fine_amount`) ✅ sudah ada.
- `03-user-auth.md` (Done) — relasi `Borrowing::user()` dan field `users.email` ✅ sudah ada.
- Akun Resend aktif + API key (`RESEND_API_KEY`).
- Domain pengirim di Resend. `onboarding@resend.dev` adalah domain sandbox Resend: cukup untuk development, **hanya boleh dikirim ke email pemilik akun**. Untuk produksi wajib ganti ke domain milik sendiri yang sudah diverifikasi di Resend.
- PHP 8.2+, Laravel 11.

## File yang Disentuh
- `plan/08-email-notifications.md` (buat) — dokumen rencana ini.
- `plan/README.md` (edit) — daftar task dan status.
- `composer.json` (edit) — tambah dependency `resend/resend-laravel ^1.7`.
- `config/mail.php` (edit) — daftarkan mailer `resend`.
- `.env` (edit) — `MAIL_MAILER`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `RESEND_API_KEY`.
- `.env.example` (edit) — key yang sama tanpa nilai rahasia.
- `app/Mail/DueDateReminder.php` (buat) — Mailable, menerima `Borrowing $borrowing`.
- `resources/views/emails/due-date-reminder.blade.php` (buat) — template email HTML inline-CSS bertema Warm Neutral.
- `app/Console/Commands/SendDueDateReminders.php` (buat) — command `pustaka:send-reminders`.
- `routes/console.php` (edit) — jadwal harian `08:00` + `withoutOverlapping()`.
- `tests/Feature/SendDueDateRemindersTest.php` (buat) — test command dan isi email.

## Langkah Eksekusi
- [x] Install `resend/resend-laravel` (`^1.7`).
- [x] Daftarkan mailer `resend` di `config/mail.php` agar `MAIL_MAILER=resend` dikenali.
- [x] Set `.env`: `MAIL_MAILER=resend`, `RESEND_API_KEY`, `MAIL_FROM_ADDRESS=onboarding@resend.dev`, `MAIL_FROM_NAME=Pustaka`.
- [x] Buat `DueDateReminder` Mailable dengan constructor `public Borrowing $borrowing`.
- [x] Subject dinamis: `Besok jatuh tempo: {judul buku}`.
- [x] Buat `emails/due-date-reminder.blade.php` — tabel `role="presentation"`, CSS inline (wajib untuk Outlook/Gmail), palet Warm Neutral (`#FAF8F5`, `#F5F3F0`, `#221F19`) + primary `#3B82F6`.
- [x] Isi email: nama user, judul buku, penulis, tanggal pinjam, tanggal jatuh tempo, CTA ke `/buku-saya`, catatan denda.
- [x] Buat command `SendDueDateReminders` dengan signature `pustaka:send-reminders`.
- [x] Query: `returned_at` null DAN `due_at` besok (`whereDate('due_at', now()->addDay())`).
- [x] Loop kirim `Mail::to($borrowing->user->email)->send(new DueDateReminder($borrowing))`.
- [x] Bungkus per-item dengan try/catch — satu email gagal tidak menghentikan sisanya; exit code `FAILURE` bila ada yang gagal.
- [x] Jadwalkan `Schedule::command('pustaka:send-reminders')->dailyAt('08:00')->withoutOverlapping()` di `routes/console.php`.
- [x] `php artisan config:clear` + verifikasi `php artisan schedule:list`.
- [x] Test: hanya kirim untuk jatuh tempo besok, lewati yang belum jatuh tempo, lewati yang sudah dikembalikan, isi email memuat data pinjaman.

## Acceptance Criteria
- `php artisan pustaka:send-reminders` dapat dijalankan dan menampilkan ringkasan `Terkirim: X, gagal: Y`.
- Hanya pinjaman dengan `returned_at` null dan `due_at` besok yang dikirim.
- Email memuat nama user, judul buku, dan tanggal jatuh tempo.
- Satu kegagalan pengiriman tidak menggagalkan pengiriman lainnya, dan tercatat di output command.
- `php artisan schedule:list` menampilkan `pustaka:send-reminders` terjadwal harian.
- Test terkait lulus.

## Catatan Operasional
- Scheduler Laravel butuh satu entri cron di server: `* * * * * cd /path/ke/project && php artisan schedule:run >> /dev/null 2>&1`.
- `withoutOverlapping()` mencegah dobel kirim bila `schedule:run` tumpang tindih. Bila butuh proteksi lintas server, tambahkan `->onOneServer()` (butuh cache lock yang valid).
- Domain `onboarding@resend.dev` hanya untuk testing dan hanya menerima di email pemilik akun. Untuk produksi, verifikasi domain di Resend lalu ganti `MAIL_FROM_ADDRESS`.
- `RESEND_API_KEY` adalah secret — jangan pernah commit `.env`. Bila key pernah terekspos, rotate di dashboard Resend.