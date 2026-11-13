<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Katalog buku publik: search, filter kategori/ketersediaan, sort, paginasi.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'available', 'sort']);

        $books = Book::query()
            ->with('category')
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('books.index', [
            'books' => $books,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    /**
     * Halaman detail buku: sinopsis, metadata, status ketersediaan.
     */
    public function show(Book $book)
    {
        $book->load('category', 'borrowings');

        return view('books.show', compact('book'));
    }
}