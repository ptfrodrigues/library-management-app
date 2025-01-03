<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Models\Catalog;

class AppController extends Controller
{
    public function index(): View
    {
        $featuredBooks = Catalog::with(['book.authors'])
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->take(5)
            ->get()
            ->pluck('book');

        $remainingCount = 5 - $featuredBooks->count();

        if ($remainingCount > 0) {
            $nonFeaturedBooks = Catalog::with(['book.authors'])
                ->where('is_featured', false)
                ->orderBy('display_order')
                ->take($remainingCount)
                ->get()
                ->pluck('book');

            $latestBooks = $featuredBooks->concat($nonFeaturedBooks);
        } else {
            $latestBooks = $featuredBooks;
        }

        $featuredAuthors = $latestBooks->flatMap(function ($book) {
            return $book->authors->map(function ($author) use ($book) {
                return [
                    'author' => $author,
                    'book' => $book
                ];
            });
        })->unique('author.id')->take(3);

        return view('index', compact('latestBooks', 'featuredAuthors'));
    }

    public function home(Request $request)
    {
        return app(CatalogViewController::class)->home($request);
    }

    public function home2(Request $request)
    {
        $bookController = new BookController();
        $data = $bookController->index($request);
        
        $books = $data->getData()['items'];
        $languages = $data->getData()['languages'];
        $genres = $data->getData()['genres'];
        $years = $data->getData()['years'];
        $authors = $data->getData()['authors'];

        if (!$books instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $perPage = 12;
            $books = new \Illuminate\Pagination\LengthAwarePaginator(
                $books->forPage($request->get('page', 1), $perPage),
                $books->count(),
                $perPage,
                $request->get('page', 1),
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        return view('pages.home', compact('books', 'languages', 'genres', 'years', 'authors'));
    }
}

