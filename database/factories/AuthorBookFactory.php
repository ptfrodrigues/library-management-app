<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class AuthorBookFactory extends Factory
{
    protected $model = Book::class;

    private const BATCH_SIZE       = 100;
    private const MAX_TITLE_LENGTH = 60;

    private static array $usedTitles = [];
    private static array $usedIsbns  = [];

    /** @var Collection|null */
    private static ?Collection $bookCache = null;

    public function definition(): array
    {
        $this->ensureBookCacheIsLoaded();

        $bookData = $this->getNextCachedBook();
        if ($bookData !== null) {
            return $bookData;
        }

        return $this->generateFakeBookData();
    }


    public function loadOpenLibraryCache(): void
    {
        $this->ensureBookCacheIsLoaded();
    }

    private function ensureBookCacheIsLoaded(): void
    {
        if (empty(self::$bookCache)) {
            self::$bookCache = $this->fetchBooksFromOpenLibrary();
        }
    }


    private function fetchBooksFromOpenLibrary(): Collection
    {
        try {
            $response = Http::get('https://openlibrary.org/search.json', [
                'q'            => 'cover_i:*',
                'limit'        => self::BATCH_SIZE,
                'has_fulltext' => 'true',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return collect($data['docs'] ?? [])
                    ->filter(fn ($book) => $this->isValidBook($book))
                    ->map(fn ($book) => $this->formatBookData($book))
                    ->values(); 
            }
        } catch (\Exception $e) {
            Log::error('Erro ao obter livros da Open Library: '.$e->getMessage());
        }

        return collect();
    }


    private function isValidBook(array $book): bool
    {
        $title = $book['title'] ?? null;
        if (
            !$title ||
            isset(self::$usedTitles[$title]) ||
            strlen($title) > self::MAX_TITLE_LENGTH ||
            empty($book['cover_i'])
        ) {
            return false;
        }
        return true;
    }


    private function getNextCachedBook(): ?array
    {
        while (self::$bookCache && self::$bookCache->isNotEmpty()) {
            $bookData = self::$bookCache->shift();
            $title    = $bookData['book']['title'];
            $isbn     = $bookData['book']['isbn'];

            if (isset(self::$usedTitles[$title]) || isset(self::$usedIsbns[$isbn])) {
                continue;
            }

            self::$usedTitles[$title] = true;
            self::$usedIsbns[$isbn]   = true;

            return $bookData;
        }

        return null;
    }

    private function formatBookData(array $book): array
    {
        $title  = $book['title'] ?? 'Untitled';
        $rawIsbn = $book['isbn'][0] ?? null;
        $isbn   = $this->getUniqueIsbn($rawIsbn);

        return [
            'book' => [
                'title'        => $title,
                'genre'        => $book['subject'][0] ?? $this->faker->word,
                'language'     => substr($book['language'][0] ?? 'eng', 0, 3),
                'isbn'         => $isbn,
                'year'         => $this->extractValidYear($book['first_publish_year'] ?? null),
                'observations' => $book['first_sentence'][0] ?? $this->faker->paragraph,
                'cover_url'    => "https://covers.openlibrary.org/b/id/{$book['cover_i']}-L.jpg",
            ],
            'authors' => $this->extractAuthors($book),
        ];
    }


    private function generateFakeBookData(): array
    {
        do {
            $title = $this->faker->unique()->sentence(3, true);
        } while (isset(self::$usedTitles[$title]) || strlen($title) > self::MAX_TITLE_LENGTH);

        self::$usedTitles[$title] = true;

        $isbn = $this->getUniqueIsbn();
        self::$usedIsbns[$isbn] = true;

        return [
            'book' => [
                'title'        => $title,
                'genre'        => $this->faker->word,
                'language'     => substr($this->faker->languageCode, 0, 3),
                'isbn'         => $isbn,
                'year'         => $this->faker->numberBetween(1000, date('Y')),
                'observations' => $this->faker->paragraph,
                'cover_url'    => $this->faker->imageUrl(300, 400, 'books'),
            ],
            'authors' => $this->generateFakeAuthors(),
        ];
    }


    private function extractAuthors(array $book): array
    {
        $authors = [];
        if (!empty($book['author_name'])) {
            foreach ($book['author_name'] as $authorName) {
                $nameParts = explode(' ', $authorName);
                $lastName  = array_pop($nameParts);
                $firstName = implode(' ', $nameParts);

                $authors[] = [
                    'first_name' => $firstName ?: $this->faker->firstName,
                    'last_name'  => $lastName  ?: $this->faker->lastName,
                    'country'    => $this->faker->country,
                ];
            }
        }

        return $authors ?: $this->generateFakeAuthors();
    }


    private function generateFakeAuthors(): array
    {
        $count   = $this->faker->numberBetween(1, 3);
        $authors = [];
        for ($i = 0; $i < $count; $i++) {
            $authors[] = [
                'first_name' => $this->faker->firstName,
                'last_name'  => $this->faker->lastName,
                'country'    => $this->faker->country,
            ];
        }
        return $authors;
    }

  
    private function extractValidYear($year): ?int
    {
        if (!$year) {
            return null;
        }

        $year = (int)$year;
        return ($year >= 1000 && $year <= date('Y')) ? $year : null;
    }

    private function getUniqueIsbn(?string $apiIsbn = null): string
    {
        if (
            $apiIsbn &&
            strlen($apiIsbn) === 13 &&
            !isset(self::$usedIsbns[$apiIsbn])
        ) {
            return $apiIsbn;
        }

        do {
            $isbn = $this->faker->unique()->isbn13;
        } while (isset(self::$usedIsbns[$isbn]));

        return $isbn;
    }
}
