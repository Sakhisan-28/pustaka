<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Pustaka — perpustakaan digital untuk siapa saja.')">
    <title>@yield('title', 'Pustaka')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-warm-50 text-warm-800 font-sans antialiased flex flex-col">

    <header class="bg-white border-b border-warm-200 sticky top-0 z-20">
        <nav class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-white font-serif font-bold text-lg">P</span>
                <span class="font-serif text-xl font-bold text-warm-900">Pustaka</span>
            </a>

            <div class="flex items-center gap-6 text-sm">
                <a href="{{ url('/katalog') }}" class="text-warm-600 hover:text-primary transition font-medium">Katalog</a>
            </div>
        </nav>
    </header>

    <main class="flex-1 w-full max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-warm-200 mt-8">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-warm-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} Pustaka. Perpustakaan digital untuk siapa saja.</p>
            <p class="font-serif italic">Hanyat, nyaman dibaca lama.</p>
        </div>
    </footer>

</body>
</html>