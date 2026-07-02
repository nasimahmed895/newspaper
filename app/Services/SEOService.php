<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class SEOService
{
    /**
     * Generate JSON-LD structured data for an article.
     */
    public function articleStructuredData(Article $article): array
    {
        $image = $article->featured_image
            ? [asset($article->featured_image)]
            : [asset('/images/placeholder-news.jpg')];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $article->title,
            'url' => route('articles.show', $article->slug),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('articles.show', $article->slug),
            ],
            'description' => $article->seo_description ?? $article->excerpt,
            'image' => $image,
            'author' => [
                '@type' => 'Person',
                'name' => $article->author ?? config('app.name'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('/images/logo.png'),
                ],
            ],
            'datePublished' => $article->published_at?->toIso8601String() ?? $article->created_at->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'articleSection' => $article->category?->name,
            'keywords' => $article->seo_keywords ?? '',
            'wordCount' => str_word_count(strip_tags($article->content)),
            'inLanguage' => 'en-US',
            'isAccessibleForFree' => true,
        ];
    }

    /**
     * Generate JSON-LD breadcrumb data.
     */
    public function breadcrumbStructuredData(array $crumbs): array
    {
        $items = [];
        $position = 1;

        foreach ($crumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $crumb['name'],
                'item' => $crumb['url'] ?? null,
            ];
            $position++;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Generate website structured data for the homepage.
     */
    public function websiteStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'potentialAction' => [
                [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/search?q={search_term_string}'),
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ];
    }

    /**
     * Generate JSON-LD structured data for a category page.
     */
    public function categoryStructuredData(Category $category): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name,
            'description' => $category->description,
            'url' => route('categories.show', $category->slug),
        ];
    }

    /**
     * Generate Open Graph meta tags array for an article.
     */
    public function openGraphTags(Article $article): array
    {
        return [
            'og:title' => $article->seo_title ?? $article->title,
            'og:description' => $article->seo_description ?? $article->excerpt,
            'og:type' => 'article',
            'og:url' => route('articles.show', $article->slug),
            'og:image' => $article->featured_image ? asset($article->featured_image) : asset('/images/placeholder-news.jpg'),
            'og:site_name' => config('app.name'),
            'og:locale' => 'en_US',
            'article:published_time' => $article->published_at?->toIso8601String() ?? $article->created_at->toIso8601String(),
            'article:modified_time' => $article->updated_at->toIso8601String(),
            'article:section' => $article->category?->name,
        ];
    }

    /**
     * Generate Twitter Card meta tags array for an article.
     */
    public function twitterCardTags(Article $article): array
    {
        return [
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $article->seo_title ?? $article->title,
            'twitter:description' => $article->seo_description ?? $article->excerpt,
            'twitter:image' => $article->featured_image ? asset($article->featured_image) : asset('/images/placeholder-news.jpg'),
        ];
    }
}
