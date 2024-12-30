<?php

namespace App\Services;

use App\Models\Catalog;
use App\Queries\CatalogQuery;
use App\Repositories\CatalogRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogService
{
    protected $catalogRepository;
    protected $catalogQuery;

    public function __construct(CatalogRepository $catalogRepository, CatalogQuery $catalogQuery)
    {
        $this->catalogRepository = $catalogRepository;
        $this->catalogQuery = $catalogQuery;
    }

    public function getAvailableBooks()
    {
        return $this->catalogRepository->getAvailableBooks();
    }

    public function create(array $data): Catalog
    {
        return $this->catalogRepository->create($data);
    }

    public function update(Catalog $catalog, array $data): bool
    {
        return $this->catalogRepository->update($catalog, $data);
    }

    public function delete(Catalog $catalog): bool
    {
        return $this->catalogRepository->delete($catalog);
    }

    public function restore(Catalog $catalog): bool
    {
        return $this->catalogRepository->restore($catalog);
    }

    public function forceDelete(Catalog $catalog): bool
    {
        return $this->catalogRepository->forceDelete($catalog);
    }

    public function moveUp(Catalog $catalog): void
    {
        $catalog->moveOrderUp();
    }

    public function moveDown(Catalog $catalog): void
    {
        $catalog->moveOrderDown();
    }

    public function getFilteredCatalog(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->catalogQuery
            ->applyFilters($filters)
            ->with(['book.authors'])
            ->paginate($perPage);
    }

    public function getFilterOptions(): array
    {
        return [
            'languages' => $this->catalogRepository->getLanguages(),
            'genres' => $this->catalogRepository->getGenres(),
            'years' => $this->catalogRepository->getYears(),
            'authors' => $this->catalogRepository->getAuthors(),
        ];
    }
}

