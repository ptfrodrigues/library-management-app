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
        $language = $request->input('language');
        $genre = $request->input('genre');
        $year = $request->input('year');
        $authorId = $request->input('author');
        $sort = $request->input('sort', 'title_asc');

        $query = Book::with('authors');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user && $user->hasRole('admin')) {
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

        if ($language) {
            $query->where('language', $language);
        }

        if ($genre) {
            $query->where('genre', $genre);
        }

        if ($year) {
            $query->where('year', $year);
        }

        if ($authorId) {
            $query->whereHas('authors', function ($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            });
        }

        switch ($sort) {
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('year', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('year', 'desc');
                break;
            default:
                $query->orderBy('title', 'asc');
        }

        $items = $query->paginate(12)->appends($request->all());
    
        $tableFields = Book::getVisibleFields();
        $fields = Book::getAllFields();

        if ($user && $user->hasRole('admin')) {
            $tableFields[] = 'deleted_at';
            $fields[] = 'deleted_at';
        }

        $languages = Book::distinct('language')->pluck('language');
        $genres = Book::distinct('genre')->pluck('genre');
        $years = Book::distinct('year')->orderBy('year', 'desc')->pluck('year');
        $authors = Author::orderBy('last_name')->get();

        return view('pages.dashboard.books', compact('items', 'tableFields', 'fields', 'languages', 'genres', 'years', 'authors'));
    }

    public function store(BookRequest $request)
    {        
        $this->authorize('create', Book::class);
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $book = Book::create($validatedData);
            if (!empty($validatedData['authors'])) {
                $book->authors()->attach($validatedData['authors']);
            }
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Book created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the book. Please try again.');
        }
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->authorize('edit_books', Book::class);

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

    public function updateFilters(Request $request)
    {
        $changedFilter = $request->input('changed_filter');
        $language = $request->input('language');
        $genre = $request->input('genre');
        $year = $request->input('year');
        $authorId = $request->input('author');

        $query = Book::query();

        if ($language && $changedFilter !== 'language-select') {
            $query->where('language', $language);
        }
        if ($genre && $changedFilter !== 'genre-select') {
            $query->where('genre', $genre);
        }
        if ($year && $changedFilter !== 'year-select') {
            $query->where('year', $year);
        }
        if ($authorId && $changedFilter !== 'author-select') {
            $query->whereHas('authors', function ($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            });
        }

        $languages = $changedFilter !== 'language-select' ? $query->clone()->distinct('language')->pluck('language') : Book::distinct('language')->pluck('language');
        $genres = $changedFilter !== 'genre-select' ? $query->clone()->distinct('genre')->pluck('genre') : Book::distinct('genre')->pluck('genre');
        $years = $changedFilter !== 'year-select' ? $query->clone()->distinct('year')->orderBy('year', 'desc')->pluck('year') : Book::distinct('year')->orderBy('year', 'desc')->pluck('year');
        $authors = $changedFilter !== 'author-select' ? Author::whereHas('books', function ($q) use ($query) {
            $q->whereIn('books.id', $query->clone()->select('id'));
        })->get() : Author::all();

        return response()->json([
            'language-select' => $languages->map(function ($language) {
                return ['value' => $language, 'label' => $language];
            }),
            'genre-select' => $genres->map(function ($genre) {
                return ['value' => $genre, 'label' => $genre];
            }),
            'year-select' => $years->map(function ($year) {
                return ['value' => $year, 'label' => $year];
            }),
            'author-select' => $authors->map(function ($author) {
                return ['value' => $author->id, 'label' => $author->first_name . ' ' . $author->last_name];
            }),
        ]);
    }
    
}

