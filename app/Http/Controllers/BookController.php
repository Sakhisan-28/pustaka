<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Katalog buku publik: search, filter kategori/ketersediaan, sort, paginasi.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'available', 'sort']);
        $search = trim((string) ($filters['search'] ?? ''));

        $books = Book::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $term = '%' . Str::lower($search) . '%';

                $query->where(function ($group) use ($term) {
                    $group->whereRaw('LOWER(title) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(author) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(isbn) LIKE ?', [$term])
                        ->orWhereHas('category', fn ($category) => $category->whereRaw('LOWER(name) LIKE ?', [$term]));
                });
            })
            ->filter($request->only(['category', 'available', 'sort']))
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