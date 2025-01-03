@props(['book'])

<div class="group relative">
    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
        <img src="{{ $book->cover_url ?? '/placeholder.svg' }}" alt="{{ $book->title }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
    </div>
    <div class="mt-4 flex justify-between">
        <div>
            <h3 class="text-sm text-gray-700">
                <a href="">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    {{ $book->title }}
                </a>
            </h3>
            <p class="mt-1 text-sm text-gray-500">{{ $book->authors->pluck('full_name')->implode(', ') }}</p>
        </div>
        <p class="text-sm font-medium text-gray-900">{{ $book->year }}</p>
    </div>
</div>

