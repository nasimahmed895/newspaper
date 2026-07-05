<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api_key', 'throttle:60,1'])->group(function () {

    // Step 1: List active categories
    Route::get('/categories', [NewsController::class, 'categories']);

    // Step 2a: Submit a manually-written article (pending review)
    // Step 2b: AI-generate an article for a category (pending review)
    // Rate-limited: 10 requests/minute
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/news/submit',   [NewsController::class, 'submit']);
        Route::post('/news/generate', [NewsController::class, 'generate']);
    });

});
