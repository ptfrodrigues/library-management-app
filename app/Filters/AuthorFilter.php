<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class AuthorFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $terms = explode(' ', $value);
        return $query->whereHas('authors', function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $q->where(function (Builder $q2) use ($term) {
                    $q2->where('first_name', 'like', "%{$term}%")
                       ->orWhere('last_name', 'like', "%{$term}%");
                });
            }
        });
    }
}
