<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    /** Maksimal pinjaman aktif per user. */
    public const MAX_ACTIVE = 3;

    /** Durasi pinjam (hari). */
    public const LOAN_DAYS = 7;

    /** Denda per hari keterlambatan (Rp). */
    public const FINE_PER_DAY = 1000;

    /** Batas keterlambatan (hari) sebelum user diblokir pinjam baru. */
    public const OVERDUE_BLOCK_DAYS = 7;

    /**
     * Pinjam buku: validasi stok & duplikasi, kurangi stok, buat record pinjaman.
     *
     * @throws \RuntimeException
     */
    public function borrow(User $user, Book $book): Borrowing
    {
        return DB::transaction(function () use ($user, $book) {
            // Kunci baris buku untuk mencegah race condition stok.
            $book = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();

            if ($book->available_copies < 1) {
                throw new \RuntimeException('Stok buku ini sedang habis.');
            }

            $activeCount = Borrowing::where('user_id', $user->id)
                ->whereNull('returned_at')
                ->count();

            if ($activeCount >= self::MAX_ACTIVE) {
                throw new \RuntimeException('Anda sudah meminjam ' . self::MAX_ACTIVE . ' buku. Kembalikan dulu sebelum meminjam lagi.');
            }

            $alreadyBorrowed = Borrowing::where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->whereNull('returned_at')
                ->exists();

            if ($alreadyBorrowed) {
                throw new \RuntimeException('Anda sudah meminjam buku ini dan belum mengembalikannya.');
            }

            // Blokir pinjaman baru jika user punya pinjaman yang terlambat > 7 hari.
            $hasLongOverdue = Borrowing::where('user_id', $user->id)
                ->whereNull('returned_at')
                ->where('due_at', '<', now()->subDays(self::OVERDUE_BLOCK_DAYS))
                ->exists();

            if ($hasLongOverdue) {
                throw new \RuntimeException('Anda memiliki pinjaman yang terlambat lebih dari ' . self::OVERDUE_BLOCK_DAYS . ' hari. Kembalikan dulu sebelum meminjam lagi.');
            }

            $book->decrement('available_copies');

            return Borrowing::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(self::LOAN_DAYS),
                'returned_at' => null,
                'extended' => false,
                'fine_amount' => 0,
            ]);
        });
    }

    /**
     * Kembalikan buku: set returned_at, tambah stok, hitung denda.
     *
     * @throws \RuntimeException
     */
    public function returnBook(Borrowing $borrowing): Borrowing
    {
        return DB::transaction(function () use ($borrowing) {
            $borrowing = Borrowing::whereKey($borrowing->id)->lockForUpdate()->firstOrFail();

            if ($borrowing->returned_at !== null) {
                throw new \RuntimeException('Buku ini sudah dikembalikan.');
            }

            $borrowing->returned_at = now();
            $borrowing->fine_amount = $this->calculateFine($borrowing);
            $borrowing->save();

            Book::whereKey($borrowing->book_id)->lockForUpdate()->firstOrFail()
                ->increment('available_copies');

            return $borrowing;
        });
    }

    /**
     * Hitung denda keterlambatan (Rp 1.000 per hari, minimal 1 hari jika terlambat).
     */
    public function calculateFine(Borrowing $borrowing): float
    {
        if (! $borrowing->due_at) {
            return 0;
        }

        $due = Carbon::parse($borrowing->due_at);
        $returned = $borrowing->returned_at ? Carbon::parse($borrowing->returned_at) : now();

        $diffSeconds = $returned->getTimestamp() - $due->getTimestamp();

        if ($diffSeconds <= 0) {
            return 0;
        }

        $daysOverdue = (int) ceil($diffSeconds / 86400);

        return (float) ($daysOverdue * self::FINE_PER_DAY);
    }
}