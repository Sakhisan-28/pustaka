@extends('layouts.app')

@section('title', 'Katalog Buku — Pustaka')
@section('description', 'Jelajahi katalog buku digital Pustaka.')

@section('content')
    <div class="mb-8">
        <h1 class="font-serif text-3xl font-bold text-warm-900">Katalog Buku</h1>
        <p class="text-warm-500 mt-1">Temukan buku digital favoritmu untuk dibaca dan dipinjam.</p>
    </div>

    {{-- Search / Filter / Sort --}}
    <form method="GET" action="{{ url('/katalog') }}"
          class="bg-white border border-warm-200 rounded-xl p-4 mb-8 grid gap-4 md:grid-cols-4 items-end">

        <div class="md:col-span-2">
            <label for="search" class="block text-sm font-medium text-warm-600 mb-1">Cari</label>
            <input type="text" id="search" name="search"
                   value="{{ $filters['search'] ?? '' }}"
                   placeholder="Judul, penulis, ISBN, atau kategori..."
                   class="w-full rounded-lg border-warm-300 bg-warm-50 focus:border-primary focus:ring-primary text-warm-800">
        </div>

        <div>
            <label for="category" class="block text-sm font-medium text-warm-600 mb-1">Kategori</label>
            <select id="category" name="category"
                    class="w-full rounded-lg border-warm-300 bg-warm-50 focus:border-primary focus:ring-primary text-warm-800">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" @selected(($filters['category'] ?? '') === $cat->slug)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="sort" class="block text-sm font-medium text-warm-600 mb-1">Urutkan</label>
            <select id="sort" name="sort"
                    class="w-full rounded-lg border-warm-300 bg-warm-50 focus:border-primary focus:ring-primary text-warm-800">
                <option value="">Terbaru</option>
                <option value="title" @selected(($filters['sort'] ?? '') === 'title')>Judul A–Z</option>
                <option value="popular" @selected(($filters['sort'] ?? '') === 'popular')>Terpopuler</option>
            </select>
        </div>

        <div class="md:col-span-4 flex items-center justify-between gap-4">
            <label class="inline-flex items-center gap-2 text-sm text-warm-600 cursor-pointer">
                <input type="checkbox" name="available" value="1"
                       @checked($filters['available'] ?? false)
                       class="rounded border-warm-300 text-primary focus:ring-primary">
                Hanya tampilkan yang tersedia
            </label>
            <button type="submit"
                    class="px-5 py-2 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition">
                Terapkan
            </button>
        </div>
    </form>

    {{-- Hasil --}}
    @if($books->isEmpty())
        <div class="text-center py-20 bg-white border border-dashed border-warm-300 rounded-xl">
            <p class="text-warm-500">Tidak ada buku yang cocok dengan pencarianmu.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $books->links() }}
        </div>
    @endif
@endsection