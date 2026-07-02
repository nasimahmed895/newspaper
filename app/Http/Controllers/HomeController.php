<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Services\SEOService;

class HomeController extends Controller
{
    protected SEOService $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

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

        // Category sections for homepage — each category with its top 3 articles
        $categorySections = Category::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($category) {
                $category->section_articles = Article::published()
                    ->where('category_id', $category->id)
                    ->with('category')
                    ->latest('published_at')
                    ->take(3)
                    ->get();
                return $category;
            })
            ->filter(fn ($cat) => $cat->section_articles->isNotEmpty());

        $websiteStructuredData = $this->seoService->websiteStructuredData();

        return view('home', compact(
            'featured',
            'latest',
            'trending',
            'categories',
            'sidebarArticles',
            'categorySections',
            'websiteStructuredData'
        ));
    }
}
