<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingProgressController extends Controller
{
    /**
     * Simpan progress bacaan terakhir user untuk sebuah buku.
     */
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'current_page' => ['required', 'integer', 'min:0'],
            'percent_completed' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        ReadingProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            [
                'current_page' => $data['current_page'],
                'percent_completed' => $data['percent_completed'],
                'last_read_at' => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }
}