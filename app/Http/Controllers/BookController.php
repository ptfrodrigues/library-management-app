<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::paginate(10);
        $title = request()->routeIs('welcome') ? 'Welcome to Our Book Library' : 'Books';
        $view = request()->routeIs('welcome') ? 'welcome' : 'books.index';
        
        return view($view, compact('books', 'title'));
    }

    /**
     * Display only soft-deleted records for Admins/Managers.
     */
    public function trashed()
    {
        $this->authorize('viewTrashed', Book::class);

        $books = Book::onlyTrashed()->paginate(10);
        return view('books.trashed', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Book::class);
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'observations' => 'nullable|string',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'observations' => 'nullable|string',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Soft delete the specified resource.
     */
    public function softDelete(Book $book)
    {
        $this->authorize('soft_delete', $book);

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book soft deleted successfully.');
    }

    /**
     * Restore a soft-deleted resource.
     */
    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $this->authorize('restore', $book);

        $book->restore();

        return redirect()->route('books.index')->with('success', 'Book restored successfully.');
    }

    /**
     * Force delete the specified resource.
     */
    public function forceDelete(Book $book)
    {
        $this->authorize('force_delete', $book);

        $book->forceDelete();

        return redirect()->route('books.index')->with('success', 'Book force deleted successfully.');
    }
}
