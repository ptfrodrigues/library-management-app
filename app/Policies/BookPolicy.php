<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Book $book): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('create_book');
    }

    public function update(User $user, Book $book): bool
    {
        return $user->can('update_book');
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->can('delete_book');
    }

    public function restore(User $user, Book $book): bool
    {
        return $user->can('restore_book');
    }

    public function forceDelete(User $user, Book $book): bool
    {
        return $user->can('force_delete_book');
    }

    public function viewTrashed(User $user): bool
    {
        return $user->can('view_trashed_books');
    }
}

