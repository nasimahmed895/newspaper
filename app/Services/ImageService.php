<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            return $this->downloadAndStore($unsplashResult);
        }

        $aiResult = $this->generateWithAI($articleTitle, $articleExcerpt);
        return $this->downloadAndStore($aiResult);
    }

    protected function downloadAndStore(array $imageResult): array
    {
        $remoteUrl = $imageResult['url'];

        if (str_starts_with($remoteUrl, '/')) {
            return $imageResult;
        }

        try {
            $response = Http::timeout(20)->get($remoteUrl);

            if (!$response->successful()) {
                return $imageResult;
            }

            $body = $response->body();

            // Reject oversized files (5 MB max)
            if (strlen($body) > 5 * 1024 * 1024) {
                Log::warning("Downloaded image exceeds 5 MB, using remote URL: {$remoteUrl}");
                return $imageResult;
            }

            // Verify it's actually a valid image
            if (!@getimagesizefromstring($body)) {
                Log::warning("Downloaded file is not a valid image, using remote URL: {$remoteUrl}");
                return $imageResult;
            }

            $contentType = $response->header('Content-Type') ?? 'image/jpeg';
            $ext = match (true) {
                str_contains($contentType, 'png')  => 'png',
                str_contains($contentType, 'webp') => 'webp',
                str_contains($contentType, 'gif')  => 'gif',
                default                            => 'jpg',
            };

            $filename = 'images/articles/' . Str::uuid() . '.' . $ext;
            Storage::disk('public')->put($filename, $body);

            return array_merge($imageResult, [
                'url'          => Storage::disk('public')->url($filename),
                'original_url' => $remoteUrl,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Image download failed, using remote URL: {$e->getMessage()}");
            return $imageResult;
        }
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
