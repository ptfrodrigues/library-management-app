<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\AuthorRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Author::class, 'author');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Author::class);

        $search = $request->input('search');

        $query = Author::with('books');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $query->withTrashed();
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhereHas('books', function ($bookQuery) use ($search) {
                      $bookQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->paginate(10)->appends(['search' => $search]);
        $tableFields = ['first_name', 'last_name', 'country', 'books'];
        $fields = array_merge(
            array_diff(Schema::getColumnListing('authors'), ['deleted_at']),
            ['books']
        );
        
        if ($user->hasRole('admin')) {
            $tableFields[] = 'deleted_at';
            $fields[] = 'deleted_at';
        }

        return view('pages.dashboard.authors', compact('items', 'tableFields', 'fields'));
    }

    public function store(AuthorRequest $request)
    {
        $validated = $request->validated();
        try {
            DB::beginTransaction();
            $author = Author::create($validated);
            DB::commit();
            return response()->json([
                'success' => true,
                'author' => [
                    'id' => $author->id,
                    'full_name' => $author->full_name,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the book. Please try again.');
        }
    }

    public function show(Author $author)
    {
        return $author->load('books');
    }

    public function update(AuthorRequest $request, Author $author)
    {
        $validated = $request->validated();
        $author->update($validated);
        $author->books()->sync($validated['books']);

        return $author->load('books');
    }

    public function destroy(Author $author)
    {
        $this->authorize('delete', $author);
        $author->delete();
        return redirect()->route('dashboard.authors')->with('success', 'Author deleted successfully.');
    }

    public function restore(Author $author)
    {
        $this->authorize('restore', $author);
        $author->restore();
        return redirect()->route('dashboard.authors')->with('success', 'Author restored successfully.');
    }

    public function forceDelete(Author $author)
    {
        $this->authorize('forceDelete', $author);
        $author->books()->detach();
        $author->forceDelete();
        return redirect()->route('dashboard.authors')->with('success', 'Author permanently deleted.');
    }
}
