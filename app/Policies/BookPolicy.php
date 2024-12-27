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
        return $user->can('create_books');
    }

    public function update(User $user, Book $book): bool
    {
        return $user->can('edit_books');
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->can('delete_books');
    }

    public function restore(User $user, Book $book): bool
    {
        return $user->can('edit_books');
    }

    public function forceDelete(User $user, Book $book): bool
    {
        return $user->can('force_delete');
    }
}

