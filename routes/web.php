<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
