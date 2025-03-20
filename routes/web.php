<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CategoryController;

// 🔑 Show login form
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// 🔑 Handle login (No password needed)
Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)
                ->where('name', $request->name)
                ->first();

    if ($user) {
        Auth::login($user); // Laravel Auth (No password)
        return redirect()->route('loans.index'); // Redirect to loans
    }

    return back()->withErrors(['login' => 'Invalid credentials.']);
})->name('login.process');

// 🔒 Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

// 🌟 All routes are now accessible for any logged-in user
Route::middleware('auth')->group(function () {
    // 📚 Loan routes
    Route::resource('/loans', LoanController::class);
    Route::post('/loans/{loan}/borrow', [LoanController::class, 'borrow'])->name('loans.borrow');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::put('/loans/{book}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::get('/borrowed-books', [LoanController::class, 'borrowedBooks'])->name('loans.borrowed');
    Route::post('/loans/comment/{book}', [LoanController::class, 'addComment'])->name('loans.comment');

    // 📖 Books (CRUD)
    Route::resource('/crud', BookController::class);
    Route::post('/books/{bookId}/borrow', [LoanController::class, 'borrow'])->name('books.borrow');

    // 📂 Categories (CRUD)
    Route::resource('/categories', CategoryController::class);
});

// 🌍 Redirect root to loans page
Route::get('/', function () {
    return redirect()->route('loans.index');
})->middleware('auth');

// 🔗 Home route (optional)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
