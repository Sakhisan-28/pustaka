<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct(private readonly BorrowingService $service)
    {
    }

    /**
     * Halaman "Buku Saya" (bookshelf) — daftar pinjaman aktif + riwayat.
     */
    public function index(Request $request)
    {
        $active = Borrowing::with('book.category')
            ->where('user_id', $request->user()->id)
            ->whereNull('returned_at')
            ->orderByDesc('borrowed_at')
            ->get();

        $history = Borrowing::with('book')
            ->where('user_id', $request->user()->id)
            ->whereNotNull('returned_at')
            ->orderByDesc('returned_at')
            ->get();

        return view('bookshelf.index', compact('active', 'history'));
    }

    /**
     * Pinjam buku.
     */
    public function borrow(Request $request, Book $book)
    {
        try {
            $this->service->borrow($request->user(), $book);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Berhasil meminjam "' . $book->title . '".');
    }

    /**
     * Kembalikan buku yang sedang dipinjam.
     */
    public function returnBook(Request $request, Borrowing $borrowing)
    {
        abort_unless($borrowing->user_id === $request->user()->id, 403, 'Bukan pinjaman Anda.');

        try {
            $returned = $this->service->returnBook($borrowing);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        $msg = 'Buku "' . ($returned->book->title ?? 'buku') . '" berhasil dikembalikan.';
        if ($returned->fine_amount > 0) {
            $msg .= ' Denda keterlambatan: Rp ' . number_format($returned->fine_amount, 0, ',', '.') . '.';
        }

        return back()->with('success', $msg);
    }
}