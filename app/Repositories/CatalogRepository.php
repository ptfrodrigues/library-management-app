<?php

namespace App\Repositories;

use App\Models\Catalog;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Facades\DB;

class CatalogRepository
{
    public function create(array $data): Catalog
    {
        return DB::transaction(function () use ($data) {
            return Catalog::create($data);
        });
    }

    public function update(Catalog $catalog, array $data): bool
    {
        return DB::transaction(function () use ($catalog, $data) {
            return $catalog->update($data);
        });
    }

    public function delete(Catalog $catalog): bool
    {
        return DB::transaction(function () use ($catalog) {
            return $catalog->delete();
        });
    }

    public function restore(Catalog $catalog): bool
    {
        return DB::transaction(function () use ($catalog) {
            return $catalog->restore();
        });
    }

    public function forceDelete(Catalog $catalog): bool
    {
        return DB::transaction(function () use ($catalog) {
            return $catalog->forceDelete();
        });
    }

    public function getLanguages()
    {
        return Book::whereHas('catalog')->distinct('language')->pluck('language');
    }

    public function getGenres()
    {
        return Book::whereHas('catalog')->distinct('genre')->pluck('genre');
    }

    public function getYears()
    {
        return Book::whereHas('catalog')->distinct('year')->orderBy('year', 'desc')->pluck('year');
    }

    public function getAuthors()
    {
        return Author::whereHas('books.catalog')->orderBy('last_name')->get();
    }

    public function getAvailableBooks()
    {
        return Book::doesntHave('catalog')->get();
    }
}