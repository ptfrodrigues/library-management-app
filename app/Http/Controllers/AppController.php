<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function home(Request $request)
    {
        $search = $request->input('search');

        $books = Book::with('authors')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->paginate(12);

        return view('pages.home', compact('books', 'search'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search', '');
    
        $books = Book::with('authors')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->get();
        return response()->json([
            'books' => $books,
        ]);
    }
    

}
