<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pustaka') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-warm-800 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-warm-50">
            <div>
                <a href="/" class="flex items-center gap-2">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary text-white font-serif font-bold text-xl">P</span>
                    <span class="font-serif text-2xl font-bold text-warm-900">Pustaka</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white border border-warm-200 shadow-sm overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-warm-400">
                &copy; {{ date('Y') }} Pustaka
            </p>
        </div>
    </body>
</html>