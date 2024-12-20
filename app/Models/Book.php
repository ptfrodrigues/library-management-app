<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'genre',
        'language',
        'isbn',
        'publication_year',
        'observations',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}

