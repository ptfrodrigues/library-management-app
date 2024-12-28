<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::get('/', [AppController::class, 'home'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Books routes
    Route::get('/dashboard/books', [BookController::class, 'index'])->name('dashboard.books');
    Route::post('/dashboard/books', [BookController::class, 'store'])->name('dashboard.books.store');
    Route::put('/dashboard/books/{book}/update', [BookController::class, 'update'])->name('dashboard.books.update');
    Route::delete('/dashboard/books/{book}', [BookController::class, 'destroy'])->name('dashboard.books.destroy');
    Route::put('/dashboard/books/{book}/restore', [BookController::class, 'restore'])->name('dashboard.books.restore')->withTrashed();
    Route::delete('/dashboard/books/{book}/force', [BookController::class, 'forceDelete'])->name('dashboard.books.forceDelete')->withTrashed();

    // Authors routes
    Route::get('/dashboard/authors', [AuthorController::class, 'index'])->name('dashboard.authors');
    Route::post('/dashboard/authors', [AuthorController::class, 'store'])->name('dashboard.authors.store');
    Route::put('/dashboard/authors/{author}/update', [AuthorController::class, 'update'])->name('dashboard.authors.update');
    Route::delete('/dashboard/authors/{author}', [AuthorController::class, 'destroy'])->name('dashboard.authors.destroy');
    Route::put('/dashboard/authors/{author}/restore', [AuthorController::class, 'restore'])->name('dashboard.authors.restore')->withTrashed();
    Route::delete('/dashboard/authors/{author}/force', [AuthorController::class, 'forceDelete'])->name('dashboard.authors.forceDelete')->withTrashed();

    // Users routes
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::post('/dashboard/users/store', [UserController::class, 'store'])->name('dashboard.user.store');
    Route::put('/dashboard/users/{user}', [UserController::class, 'update'])->name('dashboard.users.update');
    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->name('dashboard.users.destroy');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';