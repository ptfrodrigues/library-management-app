<x-guest-layout>
    <div class="space-y-6">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <h2 class="text-2xl font-display font-bold text-primary">{{ __('Log in into your Account') }}</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="email" class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-tertiary text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="password" class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-tertiary text-sm" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-secondary text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" name="remember">
                <label for="remember_me" class="ml-2 text-sm text-text">
                    {{ __('Remember me') }}
                </label>
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-accent hover:text-primary transition-colors duration-300" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

