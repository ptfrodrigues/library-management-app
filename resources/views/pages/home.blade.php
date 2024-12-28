@extends('layouts.default')

@section('content')
    <div class="container mx-auto px-4 py-16 md:py-32">
        <h1 class="text-4xl md:text-6xl font-display font-bold mb-16 md:mb-32 tracking-tight text-primary">Explore Our Collection</h1>
        
        @if(session('success') || session('error'))
            <div class="mb-12">
                @if(session('success'))
                    <div class="bg-secondary bg-opacity-10 border-l-4 border-secondary p-4 mb-4" role="alert">
                        <p class="text-secondary">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-tertiary bg-opacity-10 border-l-4 border-tertiary p-4 mb-4" role="alert">
                        <p class="text-tertiary">{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="mb-16 md:mb-32">
            <x-book-filter :languages="$languages" :genres="$genres" :years="$years" :authors="$authors" :route="route('home')"/>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 md:gap-12">
            @forelse ($books as $book)
                <x-book-card :book="$book" />
            @empty
                <p class="col-span-full text-center text-accent text-xl font-light">No books found.</p>
            @endforelse
        </div>

        <div class="mt-16 flex flex-col justify-center">
            {{ $books->links() }}
        </div>
    </div>
@endsection