<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $navCategories = Cache::remember('nav_categories', 3600, function () {
                return Category::where('is_active', true)
                    ->orderBy('order')
                    ->get(['id', 'name', 'slug']);
            });

            $view->with('navCategories', $navCategories);
        });
    }
}
