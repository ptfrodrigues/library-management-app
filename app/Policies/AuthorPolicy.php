<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;

class AuthorPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Author $author): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('create_author');
    }

    public function update(User $user, Author $author): bool
    {
        return $user->can('update_author');
    }

    public function delete(User $user, Author $author): bool
    {
        return $user->can('soft_delete_author') || $user->can('force_delete_author');
    }

    public function restore(User $user, Author $author): bool
    {
        return $user->can('soft_delete_author');
    }

    public function forceDelete(User $user, Author $author): bool
    {
        return $user->can('force_delete_author');
    }
}
