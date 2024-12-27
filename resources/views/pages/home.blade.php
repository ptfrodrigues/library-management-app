@extends('layouts.default')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Welcome to the Home Page</h1>
        <div>
            <input 
                type="text" 
                id="search-bar"
                placeholder="Search books by title..." 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                data-search-url="{{ route('search') }}"
            />
        </div>
        <div id="search-results" class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Results will appear here -->
        </div>
    </div>
@stop
