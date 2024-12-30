@extends('layouts.app')
@include('partials.hero-banner')
@section('content')
    <div class="container mx-auto pb-16 md:pb-32">        

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

