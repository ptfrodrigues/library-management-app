<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CatalogService;
use App\Models\Catalog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CatalogTable extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $language = '';
    public $genre = '';
    public $year = '';
    public $author = '';
    public $sort = 'display_order_asc';
    public $perPage = 10;
    public $readyToLoad = false;
    public $filterOptions = [];

    protected $queryString = ['search', 'language', 'genre', 'year', 'author', 'sort', 'page' => ['except' => 1]];

    protected $listeners = ['refreshCatalog' => '$refresh'];

    public function mount(CatalogService $catalogService)
    {
        $this->authorize('viewAny', Catalog::class);
        $this->filterOptions = $catalogService->getFilterOptions();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLanguage()
    {
        $this->resetPage();
    }

    public function updatingGenre()
    {
        $this->resetPage();
    }

    public function updatingYear()
    {
        $this->resetPage();
    }

    public function updatingAuthor()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function render(CatalogService $catalogService)
    {
        $catalogs = [];

        if ($this->readyToLoad) {
            $filters = [
                'search' => $this->search,
                'language' => $this->language,
                'genre' => $this->genre,
                'year' => $this->year,
                'author' => $this->author,
                'sort' => $this->sort,
            ];

            $catalogs = $catalogService->getFilteredCatalog($filters, $this->perPage);
        }

        return view('livewire.catalog-table', [
            'catalogs' => $catalogs,
        ]);
    }

    public function loadCatalogs()
    {
        $this->readyToLoad = true;
    }

    public function toggleFeatured($catalogId)
    {
        $this->authorize('update', Catalog::find($catalogId));
        
        $catalog = Catalog::findOrFail($catalogId);
        $catalog->update(['is_featured' => !$catalog->is_featured]);
    }

    public function moveUp($catalogId)
    {
        $this->authorize('update', Catalog::find($catalogId));
        
        $catalog = Catalog::findOrFail($catalogId);
        $catalog->moveOrderUp();
    }

    public function moveDown($catalogId)
    {
        $this->authorize('update', Catalog::find($catalogId));
        
        $catalog = Catalog::findOrFail($catalogId);
        $catalog->moveOrderDown();
    }

    public function removeFromCatalog($catalogId)
    {
        $this->authorize('delete', Catalog::find($catalogId));
        
        $catalog = Catalog::findOrFail($catalogId);
        $catalog->delete();
    }
}

