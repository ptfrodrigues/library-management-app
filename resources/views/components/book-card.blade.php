@props(['book'])

<div class="bg-white overflow-hidden transition-all duration-300 hover:shadow-xl group">
    <div class="aspect-w-2 aspect-h-3 bg-background relative overflow-hidden">
        @if (!empty($book->image_url))
            <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
        @else
            <div class="flex items-center justify-center w-full h-full">
                <svg class="w-16 h-16 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
        <div class="absolute inset-0 bg-primary bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-300"></div>
    </div>
    <div class="p-6">
        <h2 class="text-xl font-display font-bold text-text mb-3 leading-tight group-hover:text-primary transition-colors duration-300">{{ $book->title }}</h2>
        @if ($book->authors->isNotEmpty())
            <p class="text-sm text-accent mb-3">
                {{ $book->authors->first()->first_name }} {{ $book->authors->first()->last_name }}
                @if ($book->authors->count() > 1)
                    <span class="text-accent opacity-70"> et al.</span>
                @endif
            </p>
        @endif
        <div class="flex justify-between items-center text-xs text-accent">
            <span>{{ !empty($book->year) ? $book->year : 'Year not specified' }}</span>
            <span>{{ !empty($book->language) ? strtoupper($book->language) : 'LANG N/A' }}</span>
        </div>
        <div class="mt-3 text-xs text-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            {{ !empty($book->genre) ? $book->genre : 'Genre not specified' }}
        </div>
    </div>
</div>