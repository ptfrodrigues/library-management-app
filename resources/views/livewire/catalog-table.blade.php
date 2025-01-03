<div wire:init="loadCatalogs">
    <div wire:loading class="w-full text-center py-8">
        <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-primary border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
            <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
        </div>
    </div>
    <div class="mb-8 flex flex-wrap gap-4">
        <input wire:model.live="search" type="text" placeholder="Search catalogs..." class="w-full md:w-64 px-4 py-3 border border-secondary rounded-lg bg-background text-text focus:outline-none focus:ring-2 focus:ring-primary">

        @if(isset($filterOptions['languages']))
            <select wire:model.live="language" class="w-full md:w-auto px-4 py-3 border border-secondary rounded-lg bg-background text-text focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All Languages</option>
                @foreach($filterOptions['languages'] as $language)
                    <option value="{{ $language }}">{{ $language }}</option>
                @endforeach
            </select>
        @endif

        @if(isset($filterOptions['genres']))
            <select wire:model.live="genre" class="w-full md:w-auto px-4 py-3 border border-secondary rounded-lg bg-background text-text focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All Genres</option>
                @foreach($filterOptions['genres'] as $genre)
                    <option value="{{ $genre }}">{{ $genre }}</option>
                @endforeach
            </select>
        @endif

        @if(isset($filterOptions['years']))
            <select wire:model.live="year" class="w-full md:w-auto px-4 py-3 border border-secondary rounded-lg bg-background text-text focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All Years</option>
                @foreach($filterOptions['years'] as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        @endif

        @if(isset($filterOptions['authors']))
            <select wire:model.live="author" class="w-full md:w-auto px-4 py-3 border border-secondary rounded-lg bg-background text-text focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All Authors</option>
                @foreach($filterOptions['authors'] as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
        @endif

        <select wire:model.live="sort" class="w-full md:w-auto px-4 py-3 border border-secondary rounded-lg bg-background text-text focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="display_order_asc">Order (Ascending)</option>
            <option value="display_order_desc">Order (Descending)</option>
            <option value="title_asc">Title (A-Z)</option>
            <option value="title_desc">Title (Z-A)</option>
            <option value="year_asc">Year (Oldest First)</option>
            <option value="year_desc">Year (Newest First)</option>
        </select>
    </div>

    <div wire:loading.remove>
        @if($readyToLoad)
            <div class="overflow-x-auto rounded-lg border border-secondary">
                <table class="min-w-full divide-y divide-secondary">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-accent uppercase tracking-wider whitespace-nowrap">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-accent uppercase tracking-wider whitespace-nowrap">Book Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-accent uppercase tracking-wider whitespace-nowrap">Authors</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-accent uppercase tracking-wider whitespace-nowrap">Featured</th>
                            @can('create_catalog')<th class="px-6 py-3 text-left text-xs font-medium text-accent uppercase tracking-wider whitespace-nowrap">Actions</th>@endcan
                        </tr>
                    </thead>
                    <tbody class="bg-background divide-y divide-secondary">
                        @foreach ($catalogs as $catalog)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-text">
                                    {{ $catalog->display_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-text">
                                    {{ $catalog->book->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-text">
                                    {{ $catalog->book->authors->pluck('full_name')->join(', ') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 text-text">
                                    @can('update', $catalog)
                                        <button wire:click="toggleFeatured({{ $catalog->id }})" class="focus:outline-none">
                                            @if($catalog->is_featured)
                                                <span class="text-secondary font-semibold">Yes</span>
                                            @else
                                                <span class="text-accent">No</span>
                                            @endif
                                        </button>
                                    @else
                                        @if($catalog->is_featured)
                                            <span class="text-secondary font-semibold">Yes</span>
                                        @else
                                            <span class="text-accent">No</span>
                                        @endif
                                    @endcan
                                </td>
                                @can('edit_catalog')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm leading-5 font-medium space-x-2">
                                        <button wire:click="moveUp({{ $catalog->id }})" class="text-[#3273eae7] hover:text-secondary transition-colors duration-300" title="Move Up">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button wire:click="moveDown({{ $catalog->id }})" class="text-[#3273eae7] hover:text-secondary transition-colors duration-300" title="Move Down">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button wire:click="removeFromCatalog({{ $catalog->id }})" class="text-tertiary hover:text-primary transition-colors duration-300" title="Remove from Catalog">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $catalogs->links() }}
            </div>
        @endif
    </div>
</div>

