<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $book->title }} — Pustaka Reader</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-warm-50 text-warm-800 font-sans antialiased">
    <div id="reader-root"
         data-file-url="{{ $fileUrl }}"
         data-file-type="{{ $fileType }}"
         data-initial-page="{{ $initialPage }}"
         data-initial-percent="{{ $initialPercent }}"
         data-progress-url="{{ route('books.progress', $book) }}"
         data-book-title="{{ $book->title }}"
         class="h-full flex flex-col">

        {{-- Toolbar atas --}}
        <header class="shrink-0 bg-white border-b border-warm-200 px-3 sm:px-4 h-14 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('books.show', $book) }}"
                   class="text-warm-500 hover:text-primary transition shrink-0" title="Kembali ke detail">
                    ← <span class="hidden sm:inline">Kembali</span>
                </a>
                <span class="font-serif font-semibold text-warm-900 truncate">{{ $book->title }}</span>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-2 text-sm shrink-0">
                <span id="page-indicator" class="text-warm-500 tabular-nums mr-1"></span>

                <button id="font-dec" type="button"
                        class="px-2 py-1 rounded border border-warm-300 hover:bg-warm-100 transition"
                        title="Perkecil teks">A-</button>
                <button id="font-inc" type="button"
                        class="px-2 py-1 rounded border border-warm-300 hover:bg-warm-100 transition"
                        title="Perbesar teks">A+</button>

                <button id="theme-toggle" type="button"
                        class="px-2 py-1 rounded border border-warm-300 hover:bg-warm-100 transition"
                        title="Mode gelap / terang">🌙</button>
            </div>
        </header>

        {{-- Area baca --}}
        <main class="flex-1 overflow-hidden relative bg-warm-100">
            <div id="pdf-viewer" class="w-full h-full overflow-auto hidden p-4"></div>
            <div id="epub-viewer" class="w-full h-full hidden"></div>

            <div id="reader-loading" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-warm-400">
                <span class="text-sm">Memuat buku…</span>
            </div>

            <div id="reader-error" class="absolute inset-0 hidden flex-col items-center justify-center gap-2 text-red-500 px-6 text-center">
                <span class="text-lg font-medium">Gagal memuat buku</span>
                <span id="reader-error-msg" class="text-sm text-warm-500"></span>
            </div>
        </main>

        {{-- Navigasi bawah --}}
        <footer class="shrink-0 bg-white border-t border-warm-200 px-3 sm:px-4 h-14 flex items-center justify-center gap-3">
            <button id="prev-page" type="button"
                    class="px-4 py-1.5 rounded-lg border border-warm-300 hover:bg-warm-100 transition font-medium">
                ← Sebelumnya
            </button>
            <button id="next-page" type="button"
                    class="px-4 py-1.5 rounded-lg border border-warm-300 hover:bg-warm-100 transition font-medium">
                Berikutnya →
            </button>
        </footer>
    </div>
</body>
</html>