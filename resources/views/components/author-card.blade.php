@props(['author', 'book'])

<div class="group relative">
    <div class="w-full h-80 bg-gray-200 rounded-lg overflow-hidden">
        <img src="{{ 'https://api.dicebear.com/6.x/personas/svg?seed={$author->first_name}' ?? '/placeholder.svg' }}" alt="{{ $author->full_name }}" class="w-full h-full object-center object-cover">
    </div>
    <h3 class="mt-6 text-sm text-gray-500">
        <a href="">
            <span class="absolute inset-0"></span>
            {{ $author->full_name }}
        </a>
    </h3>
    <p class="text-base font-semibold text-gray-900">
        {{ $book->title }}
    </p>
</div>

