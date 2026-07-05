<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiPartner;
use App\Models\Article;
use App\Models\Category;
use App\Services\ImageService;
use App\Services\OpenRouterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct(
        protected OpenRouterService $openRouter,
        protected ImageService $imageService,
    ) {}

    /**
     * GET /api/v1/categories
     *
     * List all active categories. Use the returned `id` or `slug`
     * in your submit / generate requests.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'slug', 'description']);

        return response()->json(['data' => $categories]);
    }

    /**
     * POST /api/v1/news/submit
     *
     * Submit a manually-written article for review.
     * Article is saved with status=pending and is NOT published
     * until the admin approves it.
     *
     * Required headers:
     *   X-API-Key: {your_partner_key}
     *   Content-Type: application/json
     *
     * Body:
     * {
     *   "category_id":        1,                    // integer  — use id OR slug
     *   "category_slug":      "technology",         // string   — use id OR slug
     *   "title":              "Article headline",   // required, max 255 chars
     *   "content":            "<p>Full body...</p>",// required, HTML or plain text
     *   "excerpt":            "Short summary...",   // optional, auto-generated if omitted
     *   "author":             "Jane Smith",         // optional
     *   "source_url":         "https://...",        // optional, original source link
     *   "featured_image_url": "https://...",        // optional
     *   "submitted_by_name":  "Jane Smith",         // optional, submitter identity
     *   "submitted_by_email": "jane@example.com"   // optional
     * }
     *
     * Response 202:
     * {
     *   "message": "Article submitted for review.",
     *   "data": { "id": 42, "status": "pending", ... }
     * }
     */
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id'        => 'nullable|integer|exists:categories,id',
            'category_slug'      => 'nullable|string|exists:categories,slug',
            'title'              => 'required|string|max:255',
            'content'            => 'required|string|min:100',
            'excerpt'            => 'nullable|string|max:500',
            'author'             => 'nullable|string|max:100',
            'source_url'         => 'nullable|url|max:500',
            'featured_image_url' => 'nullable|url|max:500',
            'seo_title'          => 'nullable|string|max:70',
            'seo_description'    => 'nullable|string|max:160',
            'seo_keywords'       => 'nullable|string|max:500',
            'submitted_by_name'  => 'nullable|string|max:100',
            'submitted_by_email' => 'nullable|email|max:150',
        ]);

        if (empty($validated['category_id']) && empty($validated['category_slug'])) {
            return response()->json([
                'error' => 'Provide category_id or category_slug. Call GET /api/v1/categories to list options.',
            ], 422);
        }

        $category = isset($validated['category_id'])
            ? Category::find($validated['category_id'])
            : Category::where('slug', $validated['category_slug'])->first();

        if (!$category || !$category->is_active) {
            return response()->json(['error' => 'Category not found or inactive.'], 404);
        }

        /** @var ApiPartner $partner */
        $partner = $request->attributes->get('api_partner');

        $excerpt = $validated['excerpt']
            ?? Str::limit(strip_tags($validated['content']), 160);

        $slug = $this->uniqueSlug($validated['title']);

        $wordCount = str_word_count(strip_tags($validated['content']));

        $article = Article::create([
            'category_id'          => $category->id,
            'api_partner_id'       => $partner->id,
            'title'                => $validated['title'],
            'slug'                 => $slug,
            'content'              => $validated['content'],
            'excerpt'              => $excerpt,
            'author'               => $validated['author'] ?? null,
            'source_url'           => $validated['source_url'] ?? null,
            'featured_image'       => $validated['featured_image_url'] ?? null,
            'seo_title'            => $validated['seo_title'] ?? null,
            'seo_description'      => $validated['seo_description'] ?? null,
            'seo_keywords'         => $validated['seo_keywords'] ?? null,
            'submitted_by_name'    => $validated['submitted_by_name'] ?? null,
            'submitted_by_email'   => $validated['submitted_by_email'] ?? null,
            'status'               => 'pending',
            'is_published'         => false,
            'is_trending'          => false,
            'view_count'           => 0,
            'reading_time_minutes' => (int) ceil($wordCount / 200),
        ]);

        return response()->json([
            'message' => 'Article submitted for review. It will be published after admin approval.',
            'data'    => [
                'id'          => $article->id,
                'title'       => $article->title,
                'status'      => $article->status,
                'category'    => $category->name,
                // 'submitted_by'=> $partner->name,
                'created_at'  => $article->created_at->toIso8601String(),
            ],
        ], 202);
    }

    /**
     * POST /api/v1/news/generate
     *
     * AI picks a trending topic for the chosen category (or uses your topic),
     * generates a full article, and saves it as pending for admin review.
     *
     * Body:
     * {
     *   "category_id":   1,
     *   "category_slug": "technology",
     *   "topic":         "Optional topic override",
     *   "submitted_by_name":  "Jane Smith",
     *   "submitted_by_email": "jane@example.com"
     * }
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id'        => 'nullable|integer|exists:categories,id',
            'category_slug'      => 'nullable|string|exists:categories,slug',
            'topic'              => 'nullable|string|max:500',
            'submitted_by_name'  => 'nullable|string|max:100',
            'submitted_by_email' => 'nullable|email|max:150',
        ]);

        if (empty($validated['category_id']) && empty($validated['category_slug'])) {
            return response()->json([
                'error' => 'Provide category_id or category_slug. Call GET /api/v1/categories to list options.',
            ], 422);
        }

        $category = isset($validated['category_id'])
            ? Category::find($validated['category_id'])
            : Category::where('slug', $validated['category_slug'])->first();

        if (!$category || !$category->is_active) {
            return response()->json(['error' => 'Category not found or inactive.'], 404);
        }

        $topic = !empty($validated['topic'])
            ? trim($validated['topic'])
            : $this->openRouter->fetchTopicForCategory($category->name, $category->description ?? '');

        $articleData = $this->openRouter->generateArticle($topic);

        if (!$articleData) {
            return response()->json(['error' => 'AI article generation failed. Try again later.'], 503);
        }

        $imageData = $this->imageService->getFeaturedImage(
            $topic,
            $articleData['title'],
            $articleData['excerpt'],
        );

        /** @var ApiPartner $partner */
        $partner = $request->attributes->get('api_partner');

        $slug = $this->uniqueSlug($articleData['title']);

        $article = Article::create([
            'category_id'          => $category->id,
            'api_partner_id'       => $partner->id,
            'title'                => $articleData['title'],
            'slug'                 => $slug,
            'content'              => $articleData['body'],
            'excerpt'              => $articleData['excerpt'],
            'featured_image'       => $imageData['url'] ?? null,
            'image_credit'         => $imageData['credit'] ?? null,
            'image_source'         => $imageData['source'] ?? null,
            'author'               => $articleData['author'] ?? 'News Desk',
            'trending_topic'       => $topic,
            'submitted_by_name'    => $validated['submitted_by_name'] ?? null,
            'submitted_by_email'   => $validated['submitted_by_email'] ?? null,
            'status'               => 'pending',
            'is_published'         => false,
            'published_at'         => null,
            'seo_title'            => $articleData['seo_title'],
            'seo_description'      => $articleData['seo_description'],
            'seo_keywords'         => $articleData['seo_keywords'],
            'reading_time_minutes' => $articleData['reading_time_minutes'],
            'is_trending'          => false,
            'view_count'           => 0,
        ]);

        return response()->json([
            'message' => 'Article generated and submitted for review. It will be published after admin approval.',
            'data'    => [
                'id'          => $article->id,
                'title'       => $article->title,
                'status'      => $article->status,
                'topic_used'  => $topic,
                'category'    => $category->name,
                'submitted_by'=> $partner->name,
                'created_at'  => $article->created_at->toIso8601String(),
            ],
        ], 202);
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
