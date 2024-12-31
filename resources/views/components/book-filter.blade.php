@props(['languages', 'genres', 'years', 'authors', 'route'])

<form id="book-filter-form" action="{{ $route }}" class="py-8 space-y-8">
    <div id="search-container" class="flex flex-col md:flex-row md:items-center md:space-x-6">
        <div class="flex-grow  md:mb-0">
            <input 
                type="text" 
                name="search"
                id="search-input"
                placeholder="Search books" 
                value="{{ request('search') }}"
                class="w-full px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none text-sm bg-background transition-colors duration-300"
            />
        </div>
        <div class="hidden md:flex space-x-4">
            <button type="submit" class="bg-primary text-white px-8 py-3 hover:bg-secondary transition duration-300 ease-in-out text-sm font-semibold">
                Search
            </button>
            @if(request('search'))
                <button 
                    type="button" 
                    onclick="clearFilters()"
                    class="bg-secondary text-white px-8 py-3 hover:bg-primary transition duration-300 ease-in-out text-sm font-semibold"
                >
                    Clear
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <select name="language" id="language-select" class="w-full text-sm px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none filter-select bg-background transition-colors duration-300">
            <option value="">Language</option>
            @foreach($languages as $language)
                <option value="{{ $language }}" {{ request('language') == $language ? 'selected' : '' }}>{{ $language }}</option>
            @endforeach
        </select>
        <select name="genre" id="genre-select" class="w-full text-sm px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none filter-select bg-background transition-colors duration-300">
            <option value="">Genre</option>
            @foreach($genres as $genre)
                <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
            @endforeach
        </select>
        <select name="year" id="year-select" class="w-full text-sm px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none filter-select bg-background transition-colors duration-300">
            <option value="">Year</option>
            @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
        <select name="author" id="author-select" class="w-full text-sm px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none filter-select bg-background transition-colors duration-300">
            <option value="">Author</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>{{ $author->first_name }} {{ $author->last_name }}</option>
            @endforeach
        </select>
        <select name="sort" id="sort" class="w-full text-sm px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none filter-select bg-background transition-colors duration-300">
            <option value="year_desc" {{ request('sort', 'year_desc') == 'year_desc' ? 'selected' : '' }}>Newest first</option>
            <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Oldest first</option>
            <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
            <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
        </select>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        @if(request()->anyFilled(['search', 'language', 'genre', 'year', 'author']))
            <button type="button" onclick="clearFilters()" class="text-sm text-accent hover:text-primary transition duration-300 ease-in-out">
                Clear all filters
            </button>
        @endif
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('book-filter-form');
    const filterSelects = form.querySelectorAll('.filter-select');
    const searchInput = form.querySelector('input[name="search"]');

    filterSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            form.submit();
        });
    });

    searchInput.addEventListener('input', function() {
        if (this.value === '') {
            form.submit();
        }
    });
});

function clearFilters() {
    const form = document.getElementById('book-filter-form');
    const inputs = form.querySelectorAll('input[type="text"], select:not(#sort)');
    inputs.forEach(input => {
        if (input.type === 'text') {
            input.value = '';
        } else if (input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        }
    });
    form.submit();
}
</script>