@props(['book'])

@php
    $coverUrl = $book->cover_url
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($book->cover_url)
        : null;
@endphp

<a href="{{ url('/buku/' . $book->id) }}"
   class="group block bg-white rounded-xl border border-warm-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition duration-200">

    <div class="aspect-[2/3] bg-warm-100 overflow-hidden">
        @if($coverUrl)
            <img src="{{ $coverUrl }}"
                 alt="Cover {{ $book->title }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-warm-400 gap-1">
                <span class="font-serif text-3xl">📖</span>
                <span class="text-xs">Tanpa sampul</span>
            </div>
        @endif
    </div>

    <div class="p-4">
        <h3 class="font-serif font-semibold text-warm-900 leading-snug line-clamp-2">{{ $book->title }}</h3>
        <p class="text-sm text-warm-500 mt-1 truncate">{{ $book->author }}</p>

        <div class="mt-3 flex items-center justify-between">
            @if($book->available_copies > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    Tersedia ({{ $book->available_copies }})
                </span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    Stok Habis
                </span>
            @endif>

            @if($book->category)
                <span class="text-xs text-warm-400 truncate max-w-[45%]">{{ $book->category->name }}</span>
            @endif
        </div>
    </div>
</a>