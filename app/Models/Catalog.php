<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTableFields;
use App\Traits\HasDisplayOrder;
use App\Traits\VisibleFields;

class Catalog extends Model
{
    use HasFactory, VisibleFields, HasDisplayOrder;

    protected $fillable = ['book_id', 'display_order', 'is_featured'];
    protected $casts = ['is_featured' => 'boolean',];
    protected $attributes = ['is_featured' => false];
    protected static $visibleFields = ['display_order', 'book_title', 'authors', 'is_featured'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getBookTitleAttribute()
    {
        return $this->book->title ?? 'N/A';
    }

    public function getAuthorsAttribute()
    {
        return $this->book->authors->pluck('full_name')->implode(', ') ?? 'N/A';
    }

    public static function getFormFields()
    {
        return [
            'book_id' => ['type' => 'select', 'label' => 'Book', 'required' => true],
            'display_order' => ['type' => 'number', 'label' => 'Display Order', 'required' => true],
            'is_featured' => ['type' => 'checkbox', 'label' => 'Featured'],
        ];
    }
}