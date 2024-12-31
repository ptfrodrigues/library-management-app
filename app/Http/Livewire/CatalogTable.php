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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedLanguage()
    {
        $this->resetPage();
    }

    public function updatedGenre()
    {
        $this->resetPage();
    }

    public function updatedYear()
    {
        $this->resetPage();
    }

    public function updatedAuthor()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function render(CatalogService $catalogService)
    {
        $catalogs = $this->readyToLoad
            ? $catalogService->getFilteredCatalog($this->getFilters(), $this->perPage)
            : [];

        return view('livewire.catalog-table', [
            'catalogs' => $catalogs,
        ]);
    }

    public function loadCatalogs()
    {
        $this->readyToLoad = true;
    }

    public function toggleFeatured(Catalog $catalog)
    {
        $this->authorize('update', $catalog);
        $catalog->update(['is_featured' => !$catalog->is_featured]);
    }

    public function moveUp(Catalog $catalog)
    {
        $this->authorize('update', $catalog);
        $catalog->moveOrderUp();
    }

    public function moveDown(Catalog $catalog)
    {
        $this->authorize('update', $catalog);
        $catalog->moveOrderDown();
    }

    public function removeFromCatalog(Catalog $catalog)
    {
        $this->authorize('delete', $catalog);
        $catalog->delete();
    }

    protected function getFilters(): array
    {
        return [
            'search' => $this->search,
            'language' => $this->language,
            'genre' => $this->genre,
            'year' => $this->year,
            'author' => $this->author,
            'sort' => $this->sort,
        ];
    }
}

