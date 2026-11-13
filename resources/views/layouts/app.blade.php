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

            <div class="flex items-center gap-4 sm:gap-6 text-sm">
                <a href="{{ url('/katalog') }}" class="text-warm-600 hover:text-primary transition font-medium">Katalog</a>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin') }}" class="text-warm-600 hover:text-primary transition font-medium">Admin</a>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-warm-600 hover:text-primary transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="px-3.5 py-1.5 bg-primary text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Register
                    </a>
                @else
                    <span class="text-warm-700 font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-warm-600 hover:text-red-600 transition font-medium">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </nav>
    </header>

    <main class="flex-1 w-full max-w-6xl mx-auto px-4 py-8">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer class="bg-white border-t border-warm-200 mt-8">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-warm-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} Pustaka. Perpustakaan digital untuk siapa saja.</p>
            <p class="font-serif italic">Hangat, nyaman dibaca lama.</p>
        </div>
    </footer>

</body>
</html>