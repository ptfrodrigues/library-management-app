<x-guest-layout>
    <div class="space-y-6">
        <div class="text-sm text-text-secondary">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="email" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              required 
                              autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-tertiary text-sm" />
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button class="px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

