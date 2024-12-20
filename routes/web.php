<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;


use App\Http\Controllers\AppController;

Route::get('/', [AppController::class, 'home'])->name('home');
Route::get('/ajax-search', [AppController::class, 'ajaxSearch'])->name('ajax.search');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin-analytics', [DashboardController::class, 'adminAnalytics'])->name('dashboard.adminAnalytics');
    Route::get('/dashboard/user-activity', [DashboardController::class, 'userActivity'])->name('dashboard.userActivity');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
