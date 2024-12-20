<x-app-layout>
<x-dashboard-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <h1 class="text-2xl font-bold mb-4">{{ __('Welcome to the Dashboard') }}</h1>
    <p>Select an option from the navigation bar above to manage books or authors.</p>
</x-dashboard-layout>
</x-app-layout>
