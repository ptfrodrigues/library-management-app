<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Author $author): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('create_authors');
    }

    public function update(User $user, Author $author): bool
    {
        return $user->can('edit_authors');
    }

    public function delete(User $user, Author $author): bool
    {
        return $user->can('delete_authors');
    }

    public function restore(User $user, Author $author): bool
    {
        return $user->can('edit_authors');
    }

    public function forceDelete(User $user, Author $author): bool
    {
        return $user->can('force_delete');
    }
}

