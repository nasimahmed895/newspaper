<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\SEOService;

class ArticleController extends Controller
{
    protected SEOService $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->with('category')
            ->firstOrFail();

        if (!app()->runningInConsole()) {
            Article::withoutTimestamps(fn () =>
                $article->increment('view_count')
            );
        }

        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with('category')
            ->latest('published_at')
            ->take(4)
            ->get();

        $structuredData = $this->seoService->articleStructuredData($article);
        $breadcrumbs = $this->seoService->breadcrumbStructuredData([
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $article->category->name, 'url' => route('categories.show', $article->category->slug)],
            ['name' => $article->title],
        ]);
        $ogTags = $this->seoService->openGraphTags($article);
        $twitterTags = $this->seoService->twitterCardTags($article);

        return view('articles.show', compact(
            'article',
            'relatedArticles',
            'structuredData',
            'breadcrumbs',
            'ogTags',
            'twitterTags'
        ));
    }
}
