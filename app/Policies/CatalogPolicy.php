<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Catalog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatalogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view_catalog');
    }

    public function view(User $user, Catalog $catalog)
    {
        return $user->can('view_catalog');
    }

    public function create(User $user)
    {
        return $user->can('create_catalog');
    }

    public function update(User $user, Catalog $catalog)
    {
        return $user->can('edit_catalog');
    }

    public function delete(User $user, Catalog $catalog)
    {
        return $user->can('delete_catalog');
    }

    public function restore(User $user, Catalog $catalog)
    {
        return $user->can('edit_catalog');
    }

    public function forceDelete(User $user, Catalog $catalog)
    {
        return $user->can('delete_catalog') && $user->can('force_delete');
    }
}