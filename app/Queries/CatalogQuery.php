<?php

namespace App\Queries;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Builder;

class CatalogQuery
{
    protected $query;

    public function __construct()
    {
        $this->query = Catalog::query();
    }

    public function applyFilters(array $filters): self
    {
        $this->applySearchFilter($filters)
            ->applyLanguageFilter($filters)
            ->applyGenreFilter($filters)
            ->applyYearFilter($filters)
            ->applyAuthorFilter($filters)
            ->applySorting($filters);

        return $this;
    }

    public function with($relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    public function paginate($perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    protected function applySearchFilter(array $filters): self
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->query->whereHas('book', function (Builder $query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%")
                    ->orWhere('language', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%")
                    ->orWhereHas('authors', function ($query) use ($search) {
                        $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                    });
            });
        }

        return $this;
    }

    protected function applyLanguageFilter(array $filters): self
    {
        if (!empty($filters['language'])) {
            $this->query->whereHas('book', function ($query) use ($filters) {
                $query->where('language', $filters['language']);
            });
        }

        return $this;
    }

    protected function applyGenreFilter(array $filters): self
    {
        if (!empty($filters['genre'])) {
            $this->query->whereHas('book', function ($query) use ($filters) {
                $query->where('genre', $filters['genre']);
            });
        }

        return $this;
    }

    protected function applyYearFilter(array $filters): self
    {
        if (!empty($filters['year'])) {
            $this->query->whereHas('book', function ($query) use ($filters) {
                $query->where('year', $filters['year']);
            });
        }

        return $this;
    }

    protected function applyAuthorFilter(array $filters): self
    {
        if (!empty($filters['author'])) {
            $this->query->whereHas('book.authors', function ($query) use ($filters) {
                $query->where('authors.id', $filters['author']);
            });
        }

        return $this;
    }

    protected function applySorting(array $filters): self
    {
        $sort = $filters['sort'] ?? 'display_order_asc';
        switch ($sort) {
            case 'title_asc':
                $this->query->orderBy('books.title', 'asc');
                break;
            case 'title_desc':
                $this->query->orderBy('books.title', 'desc');
                break;
            case 'year_asc':
                $this->query->orderBy('books.year', 'asc');
                break;
            case 'year_desc':
                $this->query->orderBy('books.year', 'desc');
                break;
            case 'display_order_desc':
                $this->query->orderBy('catalogs.display_order', 'desc');
                break;
            default:
                $this->query->orderBy('catalogs.display_order', 'asc');
        }

        return $this;
    }
}

