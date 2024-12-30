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

    // Em vez de arrays e in_array, usamos arrays associativos para marcação rápida.
    private static array $usedTitles = [];
    private static array $usedIsbns  = [];

    /** @var Collection|null */
    private static ?Collection $bookCache = null;

    /**
     * Método padrão chamado ao fazer Book::factory()->create()
     */
    public function definition(): array
    {
        // Garante que a cache está carregada (API já chamada, se necessário).
        $this->ensureBookCacheIsLoaded();

        // Tenta obter o próximo registo da cache.
        $bookData = $this->getNextCachedBook();
        if ($bookData !== null) {
            return $bookData;
        }

        // Caso a cache esteja vazia ou sem dados válidos, gera dados fictícios.
        return $this->generateFakeBookData();
    }

    /**
     * Método para forçar a pré-carga da API antes de executar o seeder.
     */
    public function loadOpenLibraryCache(): void
    {
        $this->ensureBookCacheIsLoaded();
    }

    /**
     * Garante que a cache está carregada. Só chama a API se for nulo ou estiver vazia.
     */
    private function ensureBookCacheIsLoaded(): void
    {
        if (empty(self::$bookCache)) {
            self::$bookCache = $this->fetchBooksFromOpenLibrary();
        }
    }

    /**
     * Faz a chamada à API da OpenLibrary e devolve uma colecção com os livros filtrados.
     */
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

        // Se falhar, retorna colecção vazia
        return collect();
    }

    /**
     * Verifica se o registo do livro traz os dados mínimos e passa nas validações.
     */
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

    /**
     * Retorna o próximo livro da cache, já formatado, ou null se não houver.
     */
    private function getNextCachedBook(): ?array
    {
        // Enquanto existirem livros na cache, retira o primeiro e valida duplicados.
        while (self::$bookCache && self::$bookCache->isNotEmpty()) {
            $bookData = self::$bookCache->shift();
            $title    = $bookData['book']['title'];
            $isbn     = $bookData['book']['isbn'];

            if (isset(self::$usedTitles[$title]) || isset(self::$usedIsbns[$isbn])) {
                continue; // Se já usados, ignora e continua.
            }

            // Marca como usados e retorna.
            self::$usedTitles[$title] = true;
            self::$usedIsbns[$isbn]   = true;

            return $bookData;
        }

        return null;
    }

    /**
     * Formata o registo bruto da OpenLibrary para o array que o seeder precisa.
     */
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

    /**
     * Se não houver mais dados válidos na cache, gera dados fictícios.
     */
    private function generateFakeBookData(): array
    {
        // Gera título que não exceda 60 caracteres e não duplicado.
        do {
            $title = $this->faker->unique()->sentence(3, true);
        } while (isset(self::$usedTitles[$title]) || strlen($title) > self::MAX_TITLE_LENGTH);

        // Marca o título como utilizado.
        self::$usedTitles[$title] = true;

        // Gera ISBN único.
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

    /**
     * Extrai autores do registo ou gera fictícios se não existirem.
     */
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

    /**
     * Gera 1 a 3 autores fictícios.
     */
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

    /**
     * Retorna o ano válido, entre 1000 e o ano actual, ou null se for inválido.
     */
    private function extractValidYear($year): ?int
    {
        if (!$year) {
            return null;
        }

        $year = (int)$year;
        return ($year >= 1000 && $year <= date('Y')) ? $year : null;
    }

    /**
     * Gera um ISBN de 13 dígitos único ou valida o da API, garantindo não duplicar.
     */
    private function getUniqueIsbn(?string $apiIsbn = null): string
    {
        // Se a API forneceu um ISBN de 13 dígitos e ainda não foi usado, aproveita.
        if (
            $apiIsbn &&
            strlen($apiIsbn) === 13 &&
            !isset(self::$usedIsbns[$apiIsbn])
        ) {
            return $apiIsbn;
        }

        // Caso contrário, gera um único.
        do {
            $isbn = $this->faker->unique()->isbn13;
        } while (isset(self::$usedIsbns[$isbn]));

        return $isbn;
    }
}
