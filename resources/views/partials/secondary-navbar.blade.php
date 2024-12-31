<nav x-data="{ open: false }" >
    <div class="mx-auto">
        <div class="flex justify-between h-12">
            <div class="flex items-center">
                <div class="space-x-8 flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-accent hover:text-primary transition-colors duration-200 py-2 border-b-2 border-transparent hover:border-primary {{ request()->routeIs('dashboard') ? 'text-primary border-primary' : '' }}">
                        {{ __('Overview') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.catalogs')" :active="request()->routeIs('dashboard.catalogs')" class="text-accent hover:text-primary transition-colors duration-200 py-2 border-b-2 border-transparent hover:border-primary {{ request()->routeIs('dashboard.catalog') ? 'text-primary border-primary' : '' }}">
                        {{ __('Catalog') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.books')" :active="request()->routeIs('dashboard.books')" class="text-accent hover:text-primary transition-colors duration-200 py-2 border-b-2 border-transparent hover:border-primary {{ request()->routeIs('dashboard.books') ? 'text-primary border-primary' : '' }}">
                        {{ __('Books') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.authors')" :active="request()->routeIs('dashboard.authors')" class="text-accent hover:text-primary transition-colors duration-200 py-2 border-b-2 border-transparent hover:border-primary {{ request()->routeIs('dashboard.authors') ? 'text-primary border-primary' : '' }}">
                        {{ __('Authors') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.users')" :active="request()->routeIs('dashboard.users')" class="text-accent hover:text-primary transition-colors duration-200 py-2 border-b-2 border-transparent hover:border-primary {{ request()->routeIs('dashboard.users') ? 'text-primary border-primary' : '' }}">
                        {{ __('Users') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>