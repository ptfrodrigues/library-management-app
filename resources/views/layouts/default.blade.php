<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body class="font-sans antialiased bg-background text-text">
    <div class="flex flex-col min-h-screen">
        <header class="bg-white border-b border-secondary">
            <div class="container">
                @include('includes.header')
                @yield('header-content')
            </div>
        </header>
        <main class="flex-grow">
            <div id="main" class="py-16 container">
                @yield('content')
            </div>
        </main>

        <footer class="bg-background border-t border-secondary">
            <div class="container py-12">
                @include('includes.footer')
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>