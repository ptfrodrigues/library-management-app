<?php

namespace App\Traits;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Filters\AuthorFilter;

trait Filterable
{
    public function applyFilters($query, Request $request)
    {
        return QueryBuilder::for($query)
            ->allowedFilters($this->getAllowedFilters())
            ->allowedSorts($this->getAllowedSorts())
            ->defaultSort($this->getDefaultSort())
            ->allowedIncludes($this->getAllowedIncludes())
            ->allowedAppends($this->getAllowedAppends());
    }

    protected function getAllowedFilters(): array
    {
        return collect($this->getTableFields())->map(function ($field) {
            if ($field === 'authors') {
                return AllowedFilter::custom('authors', new AuthorFilter);
            } elseif (Str::endsWith($field, '_id')) {
                return AllowedFilter::exact($field);
            } elseif (in_array($field, ['year', 'display_order'])) {
                return AllowedFilter::scope($field);
            }
            return AllowedFilter::partial($field);
        })->toArray();
    }

    protected function getAllowedSorts(): array
    {
        return $this->getVisibleFields();
    }

    protected function getDefaultSort(): string
    {
        return $this->defaultSort ?? 'id';
    }

    protected function getAllowedIncludes(): array
    {
        return $this->allowedIncludes ?? [];
    }

    protected function getAllowedAppends(): array
    {
        return $this->allowedAppends ?? [];
    }
}
