<x-guest-layout>
    <div class="space-y-6">
        <h2 class="text-2xl font-display font-bold text-primary">{{ __('Verify Your Email') }}</h2>

        <div class="text-sm text-text-secondary">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="text-sm font-medium text-secondary bg-secondary bg-opacity-10 border border-secondary rounded-md p-4">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-md hover:bg-secondary transition-colors duration-300">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-accent text-white rounded-md hover:bg-accent-dark transition-colors duration-300">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>

