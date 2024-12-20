import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const searchBar = document.querySelector('#search-bar');
    const resultsContainer = document.querySelector('#search-results');
    let debounceTimeout;

    const renderBooks = (books) => {
        if (books.length === 0) {
            resultsContainer.innerHTML = '<p class="text-gray-500">No books found.</p>';
            return;
        }
        resultsContainer.innerHTML = books.map(book => `
            <div class="p-4 border mb-2">
                <h2 class="text-lg font-bold">${book.title}</h2>
                <p>${book.authors.map(author => author.name).join(', ')}</p>
            </div>
        `).join('');
    };

    const fetchBooks = async (query = '') => {
        resultsContainer.innerHTML = '<p class="text-gray-500">Loading...</p>';
        try {
            const response = await fetch(`${searchBar.dataset.searchUrl}?search=${encodeURIComponent(query)}`);
            const data = await response.json();
            renderBooks(data.books);
        } catch (error) {
            console.error('Error fetching books:', error);
            resultsContainer.innerHTML = '<p class="text-red-500">Error loading results. Please try again.</p>';
        }
    };

    // Initial load
    fetchBooks();

    // Search with debounce
    searchBar?.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => fetchBooks(searchBar.value.trim()), 300);
    });
});
