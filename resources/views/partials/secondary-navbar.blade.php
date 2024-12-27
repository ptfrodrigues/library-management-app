<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-10">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="space-x-6 -my-px flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Overview') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.books')" :active="request()->routeIs('dashboard.books')">
                        {{ __('Books') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.authors')" :active="request()->routeIs('dashboard.authors')">
                        {{ __('Authors') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.users')" :active="request()->routeIs('dashboard.users')">
                        {{ __('Users') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>

