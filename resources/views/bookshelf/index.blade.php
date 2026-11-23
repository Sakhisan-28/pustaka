@extends('layouts.app')

@section('title', 'Buku Saya — Pustaka')

@section('content')
    <div class="mb-8">
        <h1 class="font-serif text-3xl font-bold text-warm-900">Buku Saya</h1>
        <p class="text-warm-500 mt-1">Buku yang sedang Anda pinjam dan riwayat pengembalian.</p>
    </div>

    {{-- Sedang dipinjam --}}
    <section class="mb-10">
        <h2 class="font-serif text-xl font-semibold text-warm-900 mb-4">Sedang Dipinjam</h2>

        @if($active->isEmpty())
            <div class="text-center py-12 bg-white border border-dashed border-warm-300 rounded-xl">
                <p class="text-warm-500">Anda belum meminjam buku apa pun.</p>
                <a href="{{ url('/katalog') }}" class="text-primary hover:underline font-medium mt-2 inline-block">Jelajahi katalog →</a>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($active as $borrowing)
                    @php
                        $isOverdue = $borrowing->due_at && now()->greaterThan($borrowing->due_at);
                    @endphp
                    <div class="bg-white border border-warm-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <a href="{{ route('books.show', $borrowing->book) }}" class="shrink-0">
                                <div class="w-14 h-20 bg-warm-100 rounded-lg overflow-hidden flex items-center justify-center text-warm-400 text-xl">
                                    📖
                                </div>
                            </a>
                            <div class="min-w-0">
                                <a href="{{ route('books.show', $borrowing->book) }}" class="font-serif font-semibold text-warm-900 hover:text-primary truncate block">
                                    {{ $borrowing->book->title }}
                                </a>
                                <p class="text-sm text-warm-500 truncate">{{ $borrowing->book->author }}</p>
                                <p class="text-xs mt-1 {{ $isOverdue ? 'text-red-600 font-medium' : 'text-warm-500' }}">
                                    Dipinjam {{ $borrowing->borrowed_at?->translatedFormat('d M Y') }}
                                    · Jatuh tempo {{ $borrowing->due_at?->translatedFormat('d M Y') }}
                                    @if($isOverdue) · TERLAMBAT @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            @if($borrowing->book->file_url)
                                <a href="{{ route('books.read', $borrowing->book) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 border border-warm-300 text-warm-700 rounded-lg text-sm font-medium hover:bg-warm-100 transition">
                                    📖 Baca
                                </a>
                            @endif
                            @if($borrowing->extended)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-warm-100 border border-warm-200 text-xs font-medium text-warm-600">
                                    Sudah Diperpanjang
                                </span>
                            @elseif($borrowing->due_at && now()->lessThan($borrowing->due_at))
                                <form method="POST" action="{{ route('borrowings.extend', $borrowing) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 border border-amber-400 text-amber-700 bg-amber-50 rounded-lg text-sm font-medium hover:bg-amber-100 transition">
                                        🕒 Perpanjang 1x
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                                    ↩ Kembalikan
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Riwayat --}}
    @if($history->isNotEmpty())
        <section>
            <h2 class="font-serif text-xl font-semibold text-warm-900 mb-4">Riwayat</h2>
            <div class="bg-white border border-warm-200 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-warm-50 text-warm-500">
                        <tr>
                            <th class="text-left font-medium px-4 py-3">Buku</th>
                            <th class="text-left font-medium px-4 py-3 hidden sm:table-cell">Dipinjam</th>
                            <th class="text-left font-medium px-4 py-3">Dikembalikan</th>
                            <th class="text-right font-medium px-4 py-3">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-100">
                        @foreach($history as $borrowing)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="text-warm-800">{{ $borrowing->book->title }}</span>
                                    <span class="block text-xs text-warm-400">{{ $borrowing->book->author }}</span>
                                </td>
                                <td class="px-4 py-3 text-warm-500 hidden sm:table-cell">{{ $borrowing->borrowed_at?->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-3 text-warm-500">{{ $borrowing->returned_at?->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($borrowing->fine_amount > 0)
                                        <span class="text-red-600 font-medium">Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-warm-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection