<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebArticleController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Article routes
Route::get('/articles/{slug}', [WebArticleController::class, 'show'])->name('articles.show');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/editor', [WebArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [WebArticleController::class, 'store'])->name('articles.store');
    Route::get('/editor/{slug}', [WebArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{slug}', [WebArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{slug}', [WebArticleController::class, 'destroy'])->name('articles.destroy');
    
    // Comment routes
    Route::post('/articles/{slug}/comments', [WebArticleController::class, 'storeComment'])->name('articles.comments.store');
    Route::delete('/articles/{slug}/comments/{comment}', [WebArticleController::class, 'destroyComment'])->name('articles.comments.destroy');
    
    // Settings and profile routes (to be implemented)
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    
    Route::get('/profile/{username}', function ($username) {
        return view('profile');
    })->name('profile');
});
