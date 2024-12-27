<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Author;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Book::class);

        $search = $request->input('search');

        $query = Book::with('authors');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%")
                  ->orWhere('language', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhereHas('authors', function ($authorQuery) use ($search) {
                      $authorQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->paginate(10)->appends(['search' => $search]);
    
        $tableFields = ['title', 'genre', 'language', 'isbn', 'year', 'authors'];
        $fields = array_merge(
            array_diff(Schema::getColumnListing('books'), ['id', 'created_at', 'updated_at', 'deleted_at']),
            ['authors']
        );

        if ($user->hasRole('admin')) {
            $tableFields[] = 'deleted_at';
            $fields[] = 'deleted_at';
        }

        return view('pages.dashboard.books', compact('items', 'tableFields', 'fields'));
    }

    public function store(BookRequest $request)
    {        
        $this->authorize('create', Book::class);
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $book = Book::create($validatedData);
            $book->authors()->attach($validatedData['author_id']);
            DB::commit();
            return redirect()->route('dashboard.books.index')->with('success', 'Book created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the book. Please try again.');
        }
    }

    public function update(BookRequest $request, Book $book)
    {
        $validatedData = $request->validated();
    
        try {
            DB::beginTransaction();
            $book->update($validatedData);
            if (isset($validatedData['authors'])) {
                $book->authors()->sync($validatedData['authors']);
            }
            DB::commit();
    
            return redirect()->route('dashboard.books')->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the book: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();
        return redirect()->route('dashboard.books')->with('success', 'Book deleted successfully.');
    }

    public function restore(Book $book)
    {
        $this->authorize('restore', $book);
        $book->restore();
        return redirect()->route('dashboard.books')->with('success', 'Book restored successfully.');
    }

    public function forceDelete(Book $book)
    {
        $this->authorize('forceDelete', $book);
        $book->authors()->detach();
        $book->forceDelete();
        return redirect()->route('dashboard.books')->with('success', 'Book permanently deleted.');
    }
}

