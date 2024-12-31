@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container mx-auto py-12">
        <h1 class="text-4xl font-display font-bold mb-8 text-primary">Books</h1>

        @if(session('success'))
            <div class="bg-secondary bg-opacity-10 border border-secondary text-secondary px-6 py-4 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-tertiary bg-opacity-10 border border-tertiary text-tertiary px-6 py-4 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="mb-8">
            <form action="{{ route('dashboard.books') }}" method="GET" class="flex">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by title, author, genre, language, year or ISBN"
                    value="{{ request('search') }}"
                    class="w-full px-4 py-3 border border-secondary rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary bg-background text-text"
                >
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-r-lg hover:bg-secondary transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    Search
                </button>
            </form>
            @if(request('search'))
                <div class="mt-4">
                    <a href="{{ route('dashboard.books') }}" class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-md hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear search
                    </a>
                </div>
            @endif
        </div>
        <x-dynamic-table :items="$items" :tableFields="$tableFields" title="Books" />

        @foreach ($items as $book)
            <x-dynamic-modal :item="$book" :fields="$fields" title="Book Details" />
            
            @if($book->trashed())
                <x-dynamic-modal-action :item="$book" :route="'dashboard.books.restore'" :action="'restore'" />
                <x-dynamic-modal-action :item="$book" :route="'dashboard.books.forceDelete'" :action="'force delete'" />
            @else
                <x-dynamic-modal-edit :item="$book" :fields="$fields" title="Book" route="dashboard.books.update"/>
                <x-dynamic-modal-action :item="$book" :route="'dashboard.books.destroy'" :action="'delete'" />
            @endif
        @endforeach
    </div>
@endsection