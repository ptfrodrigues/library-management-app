<form id="book-search-form" action="{{ route('home') }}" method="GET" class="container mx-auto px-4">
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
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('book-search-form');

    searchInput.addEventListener('input', function() {
        if (this.value === '') {
            searchForm.submit();
        }
    });

    // Integrate search form with the existing filter system
    if (typeof clearFilters !== 'function') {
        window.clearFilters = function() {
            const inputs = document.querySelectorAll('#book-filter-form input[type="text"], #book-filter-form select:not(#sort), #book-search-form input[type="text"]');
            inputs.forEach(input => {
                if (input.type === 'text') {
                    input.value = '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });
            document.getElementById('book-filter-form').submit();
        }
    }
});
</script>



