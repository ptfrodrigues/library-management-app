<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-text">
    <div class="flex flex-col min-h-screen">
        <header>
            <div class="flex">
                @include('includes.header')
            </div>
        </header>
        <main class="flex-grow">
            <div id="main" class="">
                @yield('content')
            </div>
        </main>

        <footer class="bg-background border-t border-secondary">
            <div class="container pt-12">
                @include('includes.footer')
            </div>
        </footer>
    </div>
    @livewireScripts
    @stack('scripts')
</body>
</html>