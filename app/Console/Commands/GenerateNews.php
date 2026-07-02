<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use App\Models\NewsGenerationLog;
use App\Models\Setting;
use App\Services\GoogleTrendsService;
use App\Services\ImageService;
use App\Services\OpenAIService;
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

    protected OpenAIService $openAI;

    protected GoogleTrendsService $trendsService;

    protected ImageService $imageService;

    public function __construct()
    {
        parent::__construct();
        $this->openAI = app(OpenAIService::class);
        $this->trendsService = app(GoogleTrendsService::class);
        $this->imageService = app(ImageService::class);
    }

    public function handle(): int
    {
        $this->info('🚀 Starting news generation...');

        // Check if API key is configured
        if (empty(config('services.openai.api_key'))) {
            $this->error('OpenAI API key is not configured. Set OPENAI_API_KEY in your .env file.');
            return Command::FAILURE;
        }

        // Resolve configuration
        $autoPublish = $this->resolvePublishSetting();
        $limit = $this->resolveLimit();
        $specificTopic = $this->option('topic');

        // Get topics
        $topics = $specificTopic
            ? $this->getSpecificTopic($specificTopic)
            : $this->getTrendingTopics($limit);

        if (empty($topics)) {
            $this->warn('No topics available for article generation.');
            return Command::SUCCESS;
        }

        // Ensure at least one category exists
        $category = $this->getOrCreateDefaultCategory();
        if (!$category) {
            $this->error('No categories available. Create a category first in the admin panel.');
            return Command::FAILURE;
        }

        $generated = 0;
        $errors = 0;
        $log = NewsGenerationLog::create([
            'topic' => $specificTopic ?? 'trending',
            'status' => 'in_progress',
            'articles_count' => 0,
        ]);

        foreach ($topics as $index => $topicData) {
            if ($generated >= $limit) {
                break;
            }

            $topic = is_string($topicData) ? $topicData : ($topicData['title'] ?? '');
            $this->info("  [{$generated}/{$limit}] Generating article for: {$topic}");

            try {
                $result = $this->generateSingleArticle($topic, $category, $autoPublish);

                if ($result) {
                    $generated++;
                    $this->info("    ✅ Published: {$result->title}");
                } else {
                    $errors++;
                    $this->warn("    ❌ Failed to generate article for: {$topic}");
                }
            } catch (\Throwable $e) {
                $errors++;
                Log::error("News generation failed for topic '{$topic}': {$e->getMessage()}");
                $this->error("    ❌ Error: {$e->getMessage()}");
            }

            // Rate limiting delay between articles
            if ($index < count($topics) - 1) {
                usleep(1000000);
            }
        }

        // Update log
        $log->update([
            'status' => $errors === 0 ? 'success' : ($generated > 0 ? 'partial' : 'failed'),
            'articles_count' => $generated,
        ]);

        // Clear cache
        Cache::flush();

        $this->newLine();
        $this->info("✅ Done! Generated {$generated} articles with {$errors} errors.");
        $this->info("   Run 'php artisan news:update-sitemap' to regenerate the sitemap.");

        return Command::SUCCESS;
    }

    protected function generateSingleArticle(string $topic, Category $category, bool $autoPublish): ?Article
    {
        // Generate article content via OpenAI
        $articleData = $this->openAI->generateArticle($topic);

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
            'author' => 'News Desk',
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

    protected function getTrendingTopics(int $limit): array
    {
        $this->info('📡 Fetching trending topics from Google Trends...');
        $topics = $this->trendsService->fetchMultiRegionTrends($limit);
        $this->info("   Found " . count($topics) . " trending topics.");

        return $topics;
    }

    protected function getSpecificTopic(string $topic): array
    {
        $this->info("📝 Using specific topic: {$topic}");
        return [['title' => $topic, 'description' => '']];
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
