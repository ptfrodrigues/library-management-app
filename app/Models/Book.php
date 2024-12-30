<?php

namespace App\Models;

use App\Traits\HasTableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Book extends Model
{
    use HasFactory, SoftDeletes, HasTableFields;

    protected $fillable = [
        'title',
        'genre',
        'language',
        'isbn',
        'year',
        'observations',
        'cover_url',
    ];
    
    protected static $tableFields = ['title', 'genre', 'language', 'isbn', 'year', 'authors'];

    public function authors()
    {
        return $this->belongsToMany(Author::class)
                    ->withTimestamps()
                    ->withTrashed()
                    ->withPivot('deleted_at');
    }

    public function catalog()
    {
        return $this->hasOne(Catalog::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($book) {
            if (!$book->isForceDeleting()) {
                $book->authors()->updateExistingPivot($book->authors->pluck('id'), ['deleted_at' => now()]);
            }
        });

        static::restoring(function ($book) {
            $book->authors()->withTrashed()->wherePivot('deleted_at', '!=', null)->updateExistingPivot(
                $book->authors()->withTrashed()->wherePivot('deleted_at', '!=', null)->pluck('authors.id'),
                ['deleted_at' => null]
            );
        });
    }

    public function scopeWithAuthors($query)
    {
        return $query->with('authors');
    }

    public function scopeOnlyTrashedWithAuthors($query)
    {
        return $query->onlyTrashed()->with('authors');
    }

}

