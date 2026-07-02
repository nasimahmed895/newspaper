<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Search — 30 requests/minute
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
});

// Static EEAT pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/editorial-policy', [PageController::class, 'editorialPolicy'])->name('editorial-policy');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Forms — 5 submissions/minute
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
    Route::post('/newsletter', [PageController::class, 'newsletter'])->name('newsletter');
});

// Newsletter unsubscribe (signed URL — no auth needed)
Route::get('/newsletter/unsubscribe', [PageController::class, 'unsubscribe'])
    ->middleware('signed')
    ->name('newsletter.unsubscribe');
