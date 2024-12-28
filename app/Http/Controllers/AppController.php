<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function home(Request $request)
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

