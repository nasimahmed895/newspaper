<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class UpdateSitemap extends Command
{
    protected $signature = 'news:update-sitemap';

    protected $description = 'Generate and update the sitemap.xml for search engines';

    public function handle(): int
    {
        $this->info('🗺️ Generating sitemap...');

        try {
            $sitemap = Sitemap::create()
                ->add(Url::create('/')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                    ->setPriority(1.0));

            // Add category pages
            $categories = Category::where('is_active', true)->get();
            foreach ($categories as $category) {
                $sitemap->add(Url::create(route('categories.show', $category->slug, false))
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8));
            }

            // Add published articles
            $articles = Article::published()->latest('published_at')->get();
            foreach ($articles as $article) {
                $sitemap->add(Url::create(route('articles.show', $article->slug, false))
                    ->setLastModificationDate($article->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9)
                    ->addImage($article->featured_image ?? '', $article->title));
            }

            $sitemap->writeToFile(public_path('sitemap.xml'));

            $count = $articles->count() + $categories->count() + 1;
            $this->info("✅ Sitemap generated with {$count} URLs.");
            $this->info("   File: " . public_path('sitemap.xml'));

            Cache::flush();

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error("Sitemap generation failed: {$e->getMessage()}");
            $this->error("❌ Sitemap generation failed: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }
}
