<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ReaderController extends Controller
{
    /**
     * Halaman reader untuk sebuah buku.
     */
    public function show(Book $book)
    {
        abort_unless($book->file_url, 404, 'File buku tidak tersedia.');

        $type = strtolower(pathinfo($book->file_url, PATHINFO_EXTENSION));
        abort_unless(in_array($type, ['pdf', 'epub'], true), 404, 'Format file tidak didukung.');

        $progress = ReadingProgress::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        return view('reader.index', [
            'book' => $book,
            'fileType' => $type,
            'fileUrl' => route('books.file', $book),
            'initialPage' => (int) ($progress?->current_page ?? 0),
            'initialPercent' => (float) ($progress?->percent_completed ?? 0),
        ]);
    }

    /**
     * Sampaikan file buku (PDF/EPUB) ke browser untuk dirender.
     */
    public function file(Book $book): Response
    {
        abort_unless($book->file_url, 404, 'File buku tidak tersedia.');

        $disk = Storage::disk('local');
        abort_unless($disk->exists($book->file_url), 404, 'File tidak ditemukan.');

        $mime = match (strtolower(pathinfo($book->file_url, PATHINFO_EXTENSION))) {
            'pdf' => 'application/pdf',
            'epub' => 'application/epub+zip',
            default => 'application/octet-stream',
        };

        return response()->file($disk->path($book->file_url), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($book->file_url) . '"',
        ]);
    }
}