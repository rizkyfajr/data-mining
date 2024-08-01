<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="container mx-auto mt-12 p-6 bg-white rounded-lg shadow-lg max-w-md">
        @csrf

        <h2 class="text-2xl font-bold mb-4 text-blue-500">Log in</h2>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-blue-500" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                         type="password"
                         name="password"
                         required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-blue-500" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-500 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-blue-500 hover:text-blue-600" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

