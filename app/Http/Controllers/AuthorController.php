<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
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
        $authors = Author::paginate(10);
        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorRequest $request)
    {
        $validated = $request->validated();
        Author::create($validated);
        return redirect()->route('authors.index')->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorRequest $request, Author $author)
    {
        $validated = $request->validated();
        $author->update($validated);
        return redirect()->route('authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully.');
    }

    /**
     * Display a listing of the soft deleted resources.
     */
    public function trashed()
    {
        $this->authorize('viewTrashed', Author::class);
        $authors = Author::onlyTrashed()->paginate(10);
        return view('authors.trashed', compact('authors'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $author = Author::withTrashed()->findOrFail($id);
        $this->authorize('restore', $author);
        $author->restore();
        return redirect()->route('authors.index')->with('success', 'Author restored successfully.');
    }

    /**
     * Remove the specified resource permanently from storage.
     */
    public function forceDelete($id)
    {
        $author = Author::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $author);
        $author->forceDelete();
        return redirect()->route('authors.index')->with('success', 'Author permanently deleted.');
    }
}

