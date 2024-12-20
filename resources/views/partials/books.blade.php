@if($books->isNotEmpty())
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        @foreach($books as $book)
            <x-book-card :book="$book" />
        @endforeach
    </div>
    <div class="mt-6">
        {{ $books->withQueryString()->links() }}
    </div>
@else
    <p class="text-center text-gray-500">No books available.</p>
@endif
