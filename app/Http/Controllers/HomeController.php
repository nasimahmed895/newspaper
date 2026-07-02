<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Article::published()
            ->with('category')
            ->latest('published_at')
            ->take(6)
            ->get();

        $latest = Article::published()
            ->with('category')
            ->latest('published_at')
            ->take(12)
            ->get();

        $trending = Article::published()
            ->where('is_trending', true)
            ->with('category')
            ->latest('published_at')
            ->take(5)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($category) {
                $category->articles_count = $category->publishedArticles()->count();
                return $category;
            });

        $sidebarArticles = Article::published()
            ->with('category')
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('home', compact(
            'featured', 'latest', 'trending', 'categories', 'sidebarArticles'
        ));
    }
}
