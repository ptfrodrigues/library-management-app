<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Book;
use App\Policies\BookPolicy;
use App\Models\Author;
use App\Policies\AuthorPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Queries\CatalogQuery;

use Illuminate\Support\Facades\Gate;
use App\Policies\DashboardPolicy;
use Illuminate\Pagination\Paginator;
use App\Http\ViewComposers\CatalogFilterComposer;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use App\Http\Livewire\CatalogTable;

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
        $this->app->bind(CatalogQuery::class, function ($app) {
            return new CatalogQuery();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy('dashboard', DashboardPolicy::class);
        Paginator::useTailwind();
        View::composer(['home', 'admin.catalog.index'], CatalogFilterComposer::class);
        Livewire::component('catalog-table', CatalogTable::class);

    }

}

