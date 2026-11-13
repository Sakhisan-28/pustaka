<x-guest-layout>
    <h2 class="font-serif text-2xl font-bold text-warm-900">Buat Akun</h2>
    <p class="text-sm text-warm-500 mt-1 mb-6">Daftar untuk mulai membaca & meminjam.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-warm-600 hover:text-warm-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
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
            <span>G</span> Daftar dengan Google
        </a>
    @endif
</x-guest-layout>