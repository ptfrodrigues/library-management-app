@props(['action' => '', 'query' => '', 'placeholder' => 'Search...'])

<form action="{{ $action }}" method="GET" class="mb-6 w-full">
    <div class="flex items-center">
        <!-- Search Input -->
        <input 
            type="text" 
            name="search" 
            value="{{ $query }}" 
            placeholder="{{ $placeholder }}" 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
    </div>
</form>
