<x-guest-layout>
    <div class="space-y-6">
        <div class="text-sm text-text-secondary">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-accent mb-1" />
                <x-text-input id="password" 
                              class="block w-full px-4 py-3 rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
                              type="password"
                              name="password"
                              required 
                              autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-tertiary text-sm" />
            </div>

            <div class="flex justify-end">
                <x-primary-button class="px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>

