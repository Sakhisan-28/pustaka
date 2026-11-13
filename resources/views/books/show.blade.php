@extends('layouts.app')

@section('title', $book->title . ' — Pustaka')
@section('description', 'Detail buku ' . $book->title . ' oleh ' . $book->author . '.')

@section('content')
    @php
        $coverUrl = $book->cover_url
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($book->cover_url)
            : null;
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-sm text-warm-500 mb-6 flex items-center gap-2 flex-wrap">
        <a href="{{ url('/katalog') }}" class="hover:text-primary transition">Katalog</a>
        @if($book->category)
            <span class="text-warm-300">/</span>
            <a href="{{ url('/katalog?category=' . $book->category->slug) }}" class="hover:text-primary transition">
                {{ $book->category->name }}
            </a>
        @endif
        <span class="text-warm-300">/</span>
        <span class="text-warm-700 truncate max-w-[50%]">{{ $book->title }}</span>
    </nav>

    <div class="grid gap-8 md:grid-cols-3">
        {{-- Kolom kiri: cover --}}
        <div class="md:col-span-1">
            <div class="md:sticky md:top-24">
                <div class="aspect-[2/3] bg-warm-100 rounded-xl overflow-hidden border border-warm-200 shadow-sm">
                    @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-warm-400 gap-2">
                            <span class="font-serif text-5xl">📖</span>
                            <span class="text-sm">Tanpa sampul</span>
                        </div>
                    @endif
                </div>

                {{-- Status ketersediaan --}}
                <div class="mt-4 bg-white border border-warm-200 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-warm-500">Status</span>
                        @if($book->isAvailable())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Stok Habis
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm text-warm-500">Salinan tersedia</span>
                        <span class="text-sm font-medium text-warm-800">{{ $book->available_copies }} / {{ $book->total_copies }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: detail --}}
        <div class="md:col-span-2">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-warm-900 leading-tight">{{ $book->title }}</h1>
            <p class="text-warm-600 mt-2 text-lg">oleh <span class="font-medium text-warm-800">{{ $book->author }}</span></p>

            {{-- Metadata --}}
            <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                @if($book->category)
                    <div class="bg-white border border-warm-200 rounded-lg p-3">
                        <dt class="text-warm-500">Kategori</dt>
                        <dd class="font-medium text-warm-800 mt-0.5">{{ $book->category->name }}</dd>
                    </div>
                @endif
                @if($book->isbn)
                    <div class="bg-white border border-warm-200 rounded-lg p-3">
                        <dt class="text-warm-500">ISBN</dt>
                        <dd class="font-medium text-warm-800 mt-0.5">{{ $book->isbn }}</dd>
                    </div>
                @endif
                <div class="bg-white border border-warm-200 rounded-lg p-3">
                    <dt class="text-warm-500">Total salinan</dt>
                    <dd class="font-medium text-warm-800 mt-0.5">{{ $book->total_copies }}</dd>
                </div>
            </dl>

            {{-- Sinopsis --}}
            <div class="mt-8">
                <h2 class="font-serif text-xl font-semibold text-warm-900">Sinopsis</h2>
                <p class="text-warm-600 leading-relaxed mt-2 whitespace-pre-line">
                    {{ $book->description ?: 'Belum ada sinopsis untuk buku ini.' }}
                </p>
            </div>

            {{-- Tombol aksi (belum fungsional — dihubungkan di task 03 & 05) --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                @if($book->isAvailable())
                    <button type="button"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition">
                        <span>📚</span> Pinjam Buku
                    </button>
                    @if($book->file_url)
                        <a href="{{ route('books.read', $book) }}"
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-warm-300 text-warm-700 rounded-lg font-medium hover:bg-warm-100 transition">
                            <span>📖</span> Baca Sekarang
                        </a>
                    @endif
                @else
                    <button type="button"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-accent text-white rounded-lg font-medium hover:bg-amber-700 transition">
                        <span>🔔</span> Reservasi
                    </button>
                @endif

                <a href="{{ url('/katalog') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-warm-300 text-warm-700 rounded-lg font-medium hover:bg-warm-100 transition">
                    ← Kembali ke Katalog
                </a>
            </div>

            <p class="text-xs text-warm-400 mt-3">
                Tombol pinjam/reservasi & baca akan aktif setelah login (fitur dihubungkan pada task berikutnya).
            </p>
        </div>
    </div>
@endsection