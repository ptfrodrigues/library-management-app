<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * Automatically authorize resource actions for the controller.
     *
     * This applies the appropriate policy methods to the resource controller actions:
     * - index() => viewAny()
     * - create(), store() => create()
     * - show() => view()
     * - edit(), update() => update()
     * - destroy() => delete()
     *
     * Note: Custom methods like trashed(), restore(), and forceDelete() are not
     * automatically authorized and require explicit checks.
     */
    public function __construct()
    {
        $this->authorizeResource(Author::class, 'author');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('authors')->paginate(10);
        $title = request()->routeIs('welcome') ? 'Welcome to Our Book Library' : 'Books';
        $view = request()->routeIs('welcome') ? 'welcome' : 'books.index';
        
        return view($view, compact('books', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::all();
        return view('books.create', compact('authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $validated = $request->validated();
        $book = Book::create($validated);
        $book->authors()->attach($validated['authors']);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('authors');
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $validated = $request->validated();
        $book->update($validated);
        $book->authors()->sync($validated['authors']);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book soft deleted successfully.');
    }

    /**
     * Display a listing of the soft deleted resources.
     */
    public function trashed()
    {
        $this->authorize('viewTrashed', Book::class);
        $books = Book::onlyTrashed()->with('authors')->paginate(10);
        return view('books.trashed', compact('books'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $this->authorize('restore', $book);
        $book->restore();
        return redirect()->route('books.index')->with('success', 'Book restored successfully.');
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function forceDelete($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $book);
        $book->authors()->detach();
        $book->forceDelete();
        return redirect()->route('books.index')->with('success', 'Book permanently deleted.');
    }
}

