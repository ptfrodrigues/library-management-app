<div class="relative w-full h-90 md:h-128 mb-8 md:mb-16 overflow-hidden">
    <img src="{{ asset('images/hero-image-2.webp') }}" alt="Library collection showcase" class="object-cover object-center w-full h-full transition-transform duration-300 hover:scale-105">
    <div class="absolute inset-0 bg-gradient-to-b from-[#f7f7f7b3] via-[#f7f7f7b3] to-[#F7F7F7]"></div>
    <div class="absolute inset-0 flex items-center text-center justify-center p-8 md:p-16">
        <div class="block">
            <div class="flex flex-col">
                <div class="max-w-xl pt-8">
                    <h2 class="text-primary text-5xl md:text-5xl font-display font-bold mb-4 leading-tight shadow-text">
                        Discover Your Next Favorite Book
                    </h2>
                    <p class="text-sm md:text-base font-sans shadow-text mb-6">
                        Explore our vast collection and embark on new literary adventures
                    </p>
                </div>
    
                <form id="book-search-form" action="{{ route('home') }}" method="GET" class="pt-8">
                    <div id="search-container" class="flex flex-col md:flex-row md:items-center md:space-x-6">
                        <div class="flex-grow mb-6 md:mb-0">
                            <input 
                                type="text" 
                                name="search"
                                placeholder="Search books" 
                                value="{{ request('search') }}"
                                class="w-full px-4 py-3 border-b-2 border-secondary focus:border-primary focus:outline-none text-sm bg-background transition-colors duration-300"
                            />
                        </div>
                        <button type="submit" class="bg-primary text-white px-8 py-3 hover:bg-secondary transition duration-300 ease-in-out text-sm font-semibold">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>

