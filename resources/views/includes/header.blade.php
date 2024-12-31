<nav x-data="{ open: false, showSearch: false, isDashboard: {{ request()->routeIs('dashboard') || request()->routeIs('dashboard.*') ? 'true' : 'false' }}}" 
     class="fixed top-0 left-0 w-full z-50 shadow-sm bg-white/90 backdrop-blur-md"
     @scroll.window="showSearch = !isDashboard && (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) > 0.20">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16 md:h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('index') }}" class="text-primary font-display text-2xl">
                        <x-application-logo class="w-8 h-8 md:w-12 md:h-12 text-primary" />                    
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 ml-10 sm:flex">
                    @can('access_dashboard')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('pages.dashboard.*')" class="text-text hover:text-primary transition-colors duration-200">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endcan
                    @can('view_catalog')
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-text hover:text-primary transition-colors duration-200">
                            {{ __('Books') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-text hover:text-primary transition-colors duration-200 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                    <div class="space-x-8 flex items-center">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')" class="text-text hover:text-primary transition-colors duration-200">
                            {{ __('Login') }}
                        </x-nav-link>
                        @if (Route::has('register'))
                            <x-nav-link :href="route('register')" :active="request()->routeIs('register')" class="text-text hover:text-primary transition-colors duration-200">
                                {{ __('Register') }}
                            </x-nav-link>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-text hover:text-primary hover:bg-background focus:outline-none focus:bg-background focus:text-primary transition duration-200 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @can('access_dashboard')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('pages.dashboard.*')" class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-secondary">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-text">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-accent">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')" class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" class="text-text hover:text-primary hover:bg-background transition-colors duration-200">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
    <div class="container mx-auto px-4">
        @yield('header-content')
    </div>
</nav>
