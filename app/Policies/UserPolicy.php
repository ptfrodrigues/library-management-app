<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_users');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('manager')) {
            return $model->hasRole(['librarian', 'member']);
        } elseif ($user->hasRole('librarian')) {
            return $model->hasRole('member');
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create_users');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('manager')) {
            return $model->hasRole('librarian');
        }
        return false;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        } elseif ($user->hasRole('manager')) {
            return $model->hasRole('librarian');
        }
        return false;
    }
}



