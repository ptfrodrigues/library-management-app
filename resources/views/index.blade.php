@extends('layouts.app')

@section('content')
<div class="">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">Welcome to</span>
                <span class="block text-indigo-600">Our Library</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Discover a world of knowledge with our vast collection of books and resources.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mt-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Latest Books</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-5 xl:gap-x-8">
                @foreach ($latestBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        </div>

        <div class="mt-20">
            <h2 class="text-2xl font-extrabold text-gray-900">Featured Authors</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                @foreach ($featuredAuthors as $featured)
                    <x-author-card :author="$featured['author']" :book="$featured['book']" />
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

