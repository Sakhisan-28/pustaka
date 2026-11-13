<x-guest-layout>
    <h2 class="font-serif text-2xl font-bold text-warm-900">Masuk ke Pustaka</h2>
    <p class="text-sm text-warm-500 mt-1 mb-6">Selamat datang kembali.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-warm-300 text-primary shadow-sm focus:ring-primary" name="remember">
                <span class="ms-2 text-sm text-warm-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-warm-600 hover:text-warm-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if(config('services.google.client_id') && config('services.google.client_secret'))
        <div class="flex items-center gap-3 my-6">
            <span class="h-px flex-1 bg-warm-200"></span>
            <span class="text-xs text-warm-400">atau</span>
            <span class="h-px flex-1 bg-warm-200"></span>
        </div>

        <a href="{{ route('auth.google') }}"
           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-warm-300 rounded-md text-sm font-medium text-warm-700 hover:bg-warm-100 transition">
            <span>G</span> Masuk dengan Google
        </a>
    @endif

    <p class="text-sm text-warm-500 mt-6 text-center">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-primary hover:underline font-medium">Daftar</a>
    </p>
</x-guest-layout>