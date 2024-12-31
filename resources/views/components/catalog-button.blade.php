@props(['item'])

@if(auth()->user()->can('create', App\Models\Catalog::class) && !$item->catalog)
    <form action="{{ route('dashboard.catalogs.store') }}" method="POST" class="inline">
        @csrf
        <input type="hidden" name="book_id" value="{{ $item->id }}">
        <input type="hidden" name="display_order" value="0">
        <input type="hidden" name="is_featured" value="0">
        <button type="submit" class="text-primary hover:text-secondary transition-colors duration-300 inline-flex items-center px-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span class="ml-1">Catalog</span>
        </button>
    </form>
@endif

