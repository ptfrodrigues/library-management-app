<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Book $book): bool
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
        return $user->can('soft_delete_book') || $user->can('force_delete_book');
    }

    public function restore(User $user, Book $book): bool
    {
        return $user->can('soft_delete_book');
    }

    public function forceDelete(User $user, Book $book): bool
    {
        return $user->can('force_delete_book');
    }
}
