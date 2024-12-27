<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'country',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class)
                    ->withTimestamps()
                    ->withTrashed()
                    ->withPivot('deleted_at');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($author) {
            if (!$author->isForceDeleting()) {
                $author->books()->updateExistingPivot($author->books->pluck('id'), ['deleted_at' => now()]);
            }
        });

        static::restoring(function ($author) {
            $author->books()->withTrashed()->wherePivot('deleted_at', '!=', null)->updateExistingPivot(
                $author->books()->withTrashed()->wherePivot('deleted_at', '!=', null)->pluck('books.id'),
                ['deleted_at' => null]
            );
        });
    }
}

