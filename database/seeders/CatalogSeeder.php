<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Catalog;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::take(15)->get();

        foreach ($books as $index => $book) {
            Catalog::create([
                'book_id' => $book->id,
                'display_order' => $index + 1,
                'is_featured' => $index === 2
            ]);
        }

    }
}

