<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Book;
use App\Policies\BookPolicy;
use App\Models\Author;
use App\Policies\AuthorPolicy;
use App\Models\User;
use App\Policies\UserPolicy;

use Illuminate\Support\Facades\Gate;
use App\Policies\DashboardPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Book::class => BookPolicy::class,
        Author::class => AuthorPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy('dashboard', DashboardPolicy::class);
    }

}

