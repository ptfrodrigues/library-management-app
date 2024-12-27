<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', 'dashboard');

        $this->updateStatistics();
        $statistics = session('dashboard_statistics');
        $userRoles = $this->getUserRoles();

        return view('pages.dashboard.index', compact('statistics', 'userRoles'));
    }

    private function updateStatistics()
    {
        $statistics = [
            'books' => Book::count(),
            'authors' => Author::count(),
        ];

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $statistics['users'] = User::count();
            $statistics['roles'] = Role::count();
            $statistics['permissions'] = Permission::count();
        } elseif ($user->hasRole('manager')) {
            $statistics['users'] = User::role(['librarian', 'member'])->count();
        } elseif ($user->hasRole('librarian')) {
            $statistics['users'] = User::role('member')->count();
        }

        session(['dashboard_statistics' => $statistics]);
    }

    private function getUserRoles()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userRoles = [];

        if ($user->hasRole('admin')) {
            $userRoles = [
                'admins' => User::role('admin')->count(),
                'managers' => User::role('manager')->count(),
                'librarians' => User::role('librarian')->count(),
                'members' => User::role('member')->count(),
            ];
        } elseif ($user->hasRole('manager')) {
            $userRoles = [
                'librarians' => User::role('librarian')->count(),
                'members' => User::role('member')->count(),
            ];
        } elseif ($user->hasRole('librarian')) {
            $userRoles = [
                'members' => User::role('member')->count(),
            ];
        }

        return $userRoles;
    }

}

