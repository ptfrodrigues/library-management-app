@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="container mx-auto py-6">
        <h1 class="text-3xl font-bold mb-6">Books</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="mb-6">
            <form action="{{ route('dashboard.books') }}" method="GET" class="flex">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by title, author, genre, language, year or ISBN"
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Search
                </button>
            </form>
            @if(request('search'))
                <div class="mt-2">
                    <a href="{{ route('dashboard.books') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
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


