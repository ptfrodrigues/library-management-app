<x-guest-layout>
    <div class="space-y-6">
        <h2 class="text-2xl font-display font-bold text-primary">{{ __('Reset Password') }}</h2>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="email" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="email" 
                              name="email" 
                              :value="old('email', $request->email)" 
                              required 
                              autofocus 
                              autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-tertiary text-sm" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('New Password')" class="text-sm font-medium text-accent mb-1" />
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
                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="password_confirmation" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="password"
                              name="password_confirmation" 
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-tertiary text-sm" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class="px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

