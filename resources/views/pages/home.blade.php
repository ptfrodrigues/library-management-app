@extends('layouts.app')
@section('content')
    <div class="container mx-auto pb-16 md:pb-32">        

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 md:gap-12">
            @forelse ($items as $catalogEntry)
                <x-book-card :book="$catalogEntry->book" />
            @empty
                <p class="col-span-full text-center text-accent text-xl font-light">No books found.</p>
            @endforelse
        </div>

        <div class="mt-16 flex flex-col justify-center">
            {{ $items->links() }}
        </div>
    </div>
@endsection

