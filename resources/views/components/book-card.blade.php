@props(['book'])

<div class="border rounded-lg shadow-sm p-4 bg-gray-50">
    <!-- Book Title -->
    <h2 class="text-lg font-semibold text-gray-800">{{ $book->title }}</h2>

    <!-- Book Genre -->
    @if (!empty($book->genre))
        <p class="text-sm text-gray-600">Genre: {{ $book->genre }}</p>
    @else
        <p class="text-sm text-gray-600 italic text-gray-500">Genre not specified</p>
    @endif

    <!-- Publication Year -->
    @if (!empty($book->publication_year))
        <p class="text-sm text-gray-600">Publication Year: {{ $book->publication_year }}</p>
    @else
        <p class="text-sm text-gray-600 italic text-gray-500">Publication year not specified</p>
    @endif

    <!-- Authors -->
    <p class="text-sm mt-2 font-semibold">Authors:</p>
    @if ($book->authors->isNotEmpty())
        <ul class="text-sm text-gray-700 list-disc list-inside">
            @foreach ($book->authors as $author)
                <li>{{ $author->first_name }} {{ $author->last_name }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-sm italic text-gray-500">No authors available</p>
    @endif
</div>
