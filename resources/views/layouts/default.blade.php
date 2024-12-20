<!doctype html>
<html>
<head>
    @include('includes.head')
</head>
<body>
<div class="container">
   <header class="">
       @include('includes.header')
   </header>
   <div id="main" class="py-12 text-sm font-medium leading-5 text-gray-500 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           @yield('content')
   </div>
   <footer class="py-4 text-sm font-medium leading-5 text-gray-500 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
       @include('includes.footer')
   </footer>
</div>
</body>
</html>