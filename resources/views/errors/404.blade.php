<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman tidak ditemukan — Pustaka</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-warm-50 text-warm-800 font-sans antialiased flex items-center justify-center px-4 py-16">
    <main class="max-w-md w-full text-center">
        <p class="font-serif text-7xl md:text-8xl font-bold text-warm-300">404</p>
        <h1 class="font-serif text-2xl md:text-3xl font-semibold text-warm-900 mt-4">
            Buku atau halaman yang kamu cari tidak ditemukan.
        </h1>
        <p class="text-warm-500 mt-3">
            Mungkin sudah dipindahkan atau tautannya salah. Yuk, kembali menjelajah katalog.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/katalog') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition">
                ← Kembali ke Katalog
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-warm-300 text-warm-700 rounded-lg font-medium hover:bg-warm-100 transition">
                Beranda
            </a>
        </div>
        <p class="font-serif italic text-warm-400 mt-10">Hangat, nyaman dibaca lama.</p>
    </main>
</body>
</html>