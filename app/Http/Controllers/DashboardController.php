<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', 'dashboard');
    
        $books = Book::with('authors')->take(10)->get();
        return view('dashboard.index', compact('books'));
    }

    public function adminAnalytics()
    {
        $this->authorize('viewAdminAnalytics', 'dashboard');

        return view('dashboard.admin-analytics');
    }

    public function userActivity()
    {
        $this->authorize('viewActivity', 'dashboard');

        return view('dashboard.user-activity');
    }
}
