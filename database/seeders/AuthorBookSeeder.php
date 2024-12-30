<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Factories\AuthorBookFactory;

class AuthorBookSeeder extends Seeder
{
    public function run(): void
    {
        $factory = new AuthorBookFactory();

        $factory->loadOpenLibraryCache();

        DB::transaction(function () use ($factory) {
            for ($i = 0; $i < 50; $i++) {
                $data = $factory->definition();

                // Criar livro
                $book = Book::create($data['book']);

                // Preparar autores para upsert
                $authorsData = [];
                foreach ($data['authors'] as $authorData) {
                    $authorsData[] = [
                        'first_name' => $authorData['first_name'],
                        'last_name'  => $authorData['last_name'],
                        'country'    => $authorData['country'],
                    ];
                }

                // Upsert em batch
                Author::upsert(
                    $authorsData,
                    ['first_name', 'last_name'],
                    ['country']
                );

                // Buscar os IDs dos autores
                $authorIds = collect($authorsData)->map(function ($authorDatum) {
                    return Author::where('first_name', $authorDatum['first_name'])
                                 ->where('last_name',  $authorDatum['last_name'])
                                 ->value('id');
                })->filter()->all();

                // Associar autores ao livro
                $book->authors()->attach($authorIds);
            }
        });
    }
}