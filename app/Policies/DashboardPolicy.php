<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('access_dashboard');
    }

    public function viewAdminAnalytics(User $user)
    {
        return $user->hasRole('admin');
    }

    public function viewActivity(User $user)
    {
        return $user->hasRole('admin');
    }
}

