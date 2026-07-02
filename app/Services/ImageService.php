<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageService
{
    protected OpenRouterService $openRouter;

    protected ?string $unsplashAccessKey;

    public function __construct()
    {
        $this->openRouter = app(OpenRouterService::class);
        $this->unsplashAccessKey = config('services.unsplash.access_key');
    }

    public function getFeaturedImage(string $topic, string $articleTitle, string $articleExcerpt): array
    {
        $unsplashResult = $this->searchUnsplash($topic);
        if ($unsplashResult) {
            return $unsplashResult;
        }

        return $this->generateWithAI($articleTitle, $articleExcerpt);
    }

    protected function searchUnsplash(string $query): ?array
    {
        if (empty($this->unsplashAccessKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => "Client-ID {$this->unsplashAccessKey}",
                ])
                ->get('https://api.unsplash.com/search/photos', [
                    'query' => $query,
                    'orientation' => 'landscape',
                    'per_page' => 5,
                    'content_filter' => 'high',
                ]);

            if (!$response->successful()) {
                Log::warning("Unsplash search failed: {$response->status()}");

                return null;
            }

            $data = $response->json();

            if (empty($data['results'])) {
                return null;
            }

            $photo = $data['results'][0];

            return [
                'url' => $photo['urls']['regular'],
                'credit' => $photo['user']['name'],
                'credit_url' => $photo['user']['links']['html'],
                'source' => 'unsplash',
            ];
        } catch (\Throwable $e) {
            Log::warning("Unsplash search error: {$e->getMessage()}");

            return null;
        }
    }

    protected function generateWithAI(string $articleTitle, string $articleExcerpt): array
    {
        try {
            $prompt = $this->openRouter->generateImagePrompt($articleTitle, $articleExcerpt);
            $imageUrl = $this->openRouter->generateAIImage($prompt);

            if ($imageUrl) {
                return [
                    'url' => $imageUrl,
                    'credit' => 'AI Generated',
                    'credit_url' => null,
                    'source' => 'ai_generated',
                ];
            }

            return [
                'url' => '/images/placeholder-news.jpg',
                'credit' => null,
                'credit_url' => null,
                'source' => 'placeholder',
            ];
        } catch (\Throwable $e) {
            Log::warning("AI image generation failed: {$e->getMessage()}");

            return [
                'url' => '/images/placeholder-news.jpg',
                'credit' => null,
                'credit_url' => null,
                'source' => 'placeholder',
            ];
        }
    }
}
