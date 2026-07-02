<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\GoogleIndexingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SubmitToGoogleIndex extends Command
{
    protected $signature = 'news:index-google
                            {--limit=50 : Number of recent articles to submit}
                            {--type=URL_UPDATED : Type of notification (URL_UPDATED or URL_DELETED)}';

    protected $description = 'Submit published articles to Google Indexing API';

    protected GoogleIndexingService $indexingService;

    public function __construct()
    {
        parent::__construct();
        $this->indexingService = app(GoogleIndexingService::class);
    }

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $type = $this->option('type');

        if (empty(config('services.google.indexing_api_key'))) {
            $this->error('Google Indexing API key is not configured. Add GOOGLE_INDEXING_API_KEY to .env');
            return Command::FAILURE;
        }

        $this->info("📡 Submitting articles to Google Indexing API...");

        $articles = Article::published()
            ->latest('published_at')
            ->limit($limit)
            ->get();

        if ($articles->isEmpty()) {
            $this->warn('No published articles found to submit.');
            return Command::SUCCESS;
        }

        $success = 0;
        $failed = 0;

        foreach ($articles as $article) {
            $url = route('articles.show', $article->slug, true);
            $result = $this->indexingService->submitUrl($url, $type);

            if ($result['success']) {
                $success++;
                $this->info("   ✅ Submitted: {$url}");
            } else {
                $failed++;
                $this->warn("   ❌ Failed: {$url} - {$result['error']}");
            }

            // Rate limiting between requests
            usleep(200000);
        }

        $this->newLine();
        $this->info("✅ Done! Successfully submitted {$success} URLs, {$failed} failed.");

        if ($failed > 0) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
