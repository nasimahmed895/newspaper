<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\NewsGenerationLog;
use App\Models\Setting;
use App\Services\GoogleTrendsService;
use App\Services\ImageService;
use App\Services\OpenRouterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateNews extends Command
{
    protected $signature = 'news:generate
                            {--topic= : Specific topic to write about}
                            {--limit= : Number of articles to generate}
                            {--publish= : Auto-publish generated articles (true/false)}';

    protected $description = 'Generate news articles from trending topics using AI';

    protected OpenRouterService $openRouter;

    protected GoogleTrendsService $trendsService;

    protected ImageService $imageService;

    public function __construct()
    {
        parent::__construct();
        $this->openRouter = app(OpenRouterService::class);
        $this->trendsService = app(GoogleTrendsService::class);
        $this->imageService = app(ImageService::class);
    }

    public function handle(): int
    {
        $this->info('🚀 Starting news generation...');

        if (empty(config('services.openrouter.api_key'))) {
            $this->error('OpenRouter API key is not configured. Set OPENROUTER_API_KEY in your .env file.');

            return Command::FAILURE;
        }

        $autoPublish   = $this->resolvePublishSetting();
        $limit         = $this->resolveLimit();
        $specificTopic = $this->option('topic');

        // Load active categories ordered by priority
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        if ($categories->isEmpty()) {
            $categories = collect([$this->getOrCreateDefaultCategory()])->filter();
        }

        if ($categories->isEmpty()) {
            $this->error('No categories available. Create a category first in the admin panel.');
            return Command::FAILURE;
        }

        $this->info("📂 Found {$categories->count()} active categories.");

        $generated = 0;
        $errors    = 0;
        $log = NewsGenerationLog::create([
            'topic'          => $specificTopic ?? 'category-based',
            'status'         => 'in_progress',
            'articles_count' => 0,
        ]);

        // Build category queue: cycle through categories up to $limit slots
        $categoryList = $categories->values();
        $totalCats    = $categoryList->count();

        for ($index = 0; $index < $limit; $index++) {
            $category = $categoryList[$index % $totalCats];

            // Topic: use CLI option on first slot only, else fetch per category
            $topic = ($specificTopic && $index === 0)
                ? $specificTopic
                : $this->getTopicForCategory($category);

            $this->info("  [{$generated}/{$limit}] [{$category->name}] Topic: {$topic}");

            try {
                $result = $this->generateSingleArticle($topic, $category, $autoPublish);

                if ($result) {
                    $generated++;
                    $this->info("    ✅ Published: {$result->title}");
                } else {
                    $errors++;
                    $this->warn("    ❌ Failed for: {$topic}");
                }
            } catch (\Throwable $e) {
                $errors++;
                Log::error("News generation failed for topic '{$topic}': {$e->getMessage()}");
                $this->error("    ❌ Error: {$e->getMessage()}");
            }

            if ($index < $limit - 1) {
                usleep(1000000);
            }
        }

        $status = $errors === 0 ? 'success' : ($generated > 0 ? 'partial' : 'failed');

        $log->update([
            'status'         => $status,
            'articles_count' => $generated,
        ]);

        if ($status === 'failed') {
            Log::error("[news:generate] COMPLETE FAILURE — 0 articles generated, {$errors} errors.");
            $this->sendFailureAlert($errors);
        } elseif ($status === 'partial') {
            Log::warning("[news:generate] Partial run — {$generated} generated, {$errors} failed.");
        }

        Cache::flush();

        $this->newLine();
        $this->info("✅ Done! Generated {$generated} articles with {$errors} errors.");
        $this->info("   Run 'php artisan news:update-sitemap' to regenerate the sitemap.");

        return Command::SUCCESS;
    }

    protected function getTopicForCategory(Category $category): string
    {
        $this->info("    📡 Fetching trending topic for [{$category->name}]...");

        $recentTitles = \App\Models\Article::where('category_id', $category->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->pluck('trending_topic')
            ->map(fn ($t) => strtolower(trim($t ?? '')))
            ->filter()
            ->all();

        $trends = $this->trendsService->fetchMultiRegionTrends(20);
        $categoryKeywords = strtolower($category->name . ' ' . ($category->description ?? ''));

        foreach ($trends as $trend) {
            $title    = strtolower($trend['title'] ?? '');
            $titleKey = strtolower(trim($trend['title'] ?? ''));

            // Skip if written in last 24h
            if (in_array($titleKey, $recentTitles, true)) {
                continue;
            }

            foreach (explode(' ', $categoryKeywords) as $keyword) {
                if (strlen($keyword) > 3 && str_contains($title, $keyword)) {
                    $this->info("    ✓ Matched Google Trend: {$trend['title']}");
                    return $trend['title'];
                }
            }
        }

        return $this->openRouter->fetchTopicForCategory(
            $category->name,
            $category->description ?? ''
        );
    }

    protected function generateSingleArticle(string $topic, Category $category, bool $autoPublish): ?Article
    {
        $articleData = $this->openRouter->generateArticle($topic);

        if (!$articleData) {
            return null;
        }

        // Check for duplicate titles
        $existing = Article::where('title', $articleData['title'])->first();
        if ($existing) {
            $articleData['title'] = $articleData['title'] . ' - Update';
            $articleData['slug'] = \Illuminate\Support\Str::slug($articleData['title']);
        }

        // Get featured image
        $imageResult = $this->imageService->getFeaturedImage(
            $topic,
            $articleData['title'],
            $articleData['excerpt']
        );

        // Create article
        $article = Article::create([
            'category_id' => $category->id,
            'title' => $articleData['title'],
            'content' => $articleData['body'],
            'excerpt' => $articleData['excerpt'],
            'featured_image' => $imageResult['url'],
            'image_credit' => $imageResult['credit'],
            'image_source' => $imageResult['source'],
            'author' => $articleData['author'] ?? 'News Desk',
            'source' => 'Trending Topics',
            'trending_topic' => $topic,
            'is_published' => $autoPublish,
            'published_at' => $autoPublish ? now() : null,
            'seo_title' => $articleData['seo_title'],
            'seo_description' => $articleData['seo_description'],
            'seo_keywords' => $articleData['seo_keywords'],
            'reading_time_minutes' => $articleData['reading_time_minutes'],
            'is_trending' => true,
        ]);

        return $article;
    }

    protected function sendFailureAlert(int $errors): void
    {
        $adminEmail = config('app.admin_email');
        if (!$adminEmail) {
            return;
        }

        try {
            \Illuminate\Support\Facades\Mail::raw(
                "WorldPulse24 news generation FAILED.\n\n{$errors} error(s), 0 articles published.\n\nCheck laravel.log for details.",
                fn ($m) => $m->to($adminEmail)->subject('[WorldPulse24] News Generation FAILED')
            );
        } catch (\Throwable $e) {
            Log::error("Failed to send failure alert email: {$e->getMessage()}");
        }
    }

    protected function getOrCreateDefaultCategory(): ?Category
    {
        $category = Category::where('is_active', true)->first();

        if (!$category) {
            $this->warn('No active categories found. Creating default category...');
            $category = Category::create([
                'name' => 'General',
                'slug' => 'general',
                'description' => 'General news and trending topics',
                'is_active' => true,
                'order' => 0,
            ]);
        }

        return $category;
    }

    protected function resolvePublishSetting(): bool
    {
        $publishOption = $this->option('publish');
        if ($publishOption !== null) {
            return filter_var($publishOption, FILTER_VALIDATE_BOOLEAN);
        }

        return filter_var(Setting::getValue('automation.auto_publish', env('NEWS_DEFAULT_PUBLISH', 'true')), FILTER_VALIDATE_BOOLEAN);
    }

    protected function resolveLimit(): int
    {
        $limitOption = $this->option('limit');
        if ($limitOption !== null) {
            return (int) $limitOption;
        }

        return (int) Setting::getValue('automation.max_articles', env('NEWS_MAX_ARTICLES_PER_RUN', 5));
    }
}
