<x-guest-layout>
    <div class="space-y-6">
        <h2 class="text-2xl font-display font-bold text-primary">{{ __('Create an Account') }}</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="name" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="text" 
                              name="name" 
                              :value="old('name')" 
                              required 
                              autofocus 
                              autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-tertiary text-sm" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="email" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              required 
                              autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-tertiary text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="password" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="password"
                              name="password"
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-tertiary text-sm" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="password_confirmation" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="password"
                              name="password_confirmation" 
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-tertiary text-sm" />
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-accent hover:text-primary transition-colors duration-300" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

