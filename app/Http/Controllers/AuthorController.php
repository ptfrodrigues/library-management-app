<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\AuthorRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $items = $query->paginate(12)->appends(['search' => $search]);
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
            Author::create($validated);
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Author created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the author. Please try again.');
        }
    }

    public function show(Author $author)
    {
        return $author->load('books');
    }

    public function update(AuthorRequest $request, Author $author)
    {
        $this->authorize('edit_authors', Author::class);

        $validated = $request->validated();

        try {
            DB::beginTransaction();
        
            $author->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'country' => $validated['country'],
            ]);
        
            if (isset($validated['books'])) {
                $author->books()->sync($validated['books']);
            } else {
                $author->books()->detach();
            }
        
            DB::commit();
        
            return redirect()->route('dashboard.authors')->with('success', 'Author updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating author: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the author: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Author $author)
    {
        $this->authorize('delete', $author);
        try {
            DB::beginTransaction();
            $author->delete();
            DB::commit();
            return redirect()->route('dashboard.authors')->with('success', 'Author deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting author: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the author: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        $author = Author::withTrashed()->findOrFail($id);
        $this->authorize('restore', $author);
        try {
            DB::beginTransaction();
            $author->restore();
            $author->books()->withTrashed()->updateExistingPivot(
                $author->books()->withTrashed()->pluck('books.id'),
                ['deleted_at' => null]
            );
            DB::commit();
            return redirect()->route('dashboard.authors')->with('success', 'Author restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error restoring author: ' . $e->getMessage(), ['author_id' => $id, 'exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while restoring the author: ' . $e->getMessage());
        }
    }

    public function forceDelete(Author $author)
    {
        $this->authorize('forceDelete', $author);
        try {
            DB::beginTransaction();
            $author->books()->detach();
            $author->forceDelete();
            DB::commit();
            return redirect()->route('dashboard.authors')->with('success', 'Author permanently deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error force deleting author: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while permanently deleting the author: ' . $e->getMessage());
        }
    }
}

