<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function home(Request $request)
    {
        $search = $request->input('search');
        $query = Book::with('authors');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%")
                  ->orWhereHas('authors', function ($authorQuery) use ($search) {
                      $authorQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                  });
            });
        }

        $books = $query->paginate(20)->appends(['search' => $search]);

        return view('pages.home', compact('books'));
    }
}

