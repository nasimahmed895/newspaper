<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI;

class OpenRouterService
{
    protected \OpenAI\Client $client;

    protected string $textModel;

    protected string $imageModel;

    protected string $searchModel;

    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.openrouter.base_url', 'https://openrouter.ai/api/v1'), '/');

        $this->client = OpenAI::factory()
            ->withApiKey(config('services.openrouter.api_key'))
            ->withBaseUri($this->baseUrl)
            ->withHttpHeader('HTTP-Referer', config('app.url'))
            ->withHttpHeader('X-Title', config('app.name'))
            ->make();

        $this->textModel = config('services.openrouter.text_model', 'openai/gpt-4o-mini');
        $this->imageModel = config('services.openrouter.image_model', 'bytedance/sdxl-lightning-4step');
        $this->searchModel = config('services.openrouter.search_model', 'google/gemini-2.5-flash');
    }

    public function generateArticle(string $topic, int $maxRetries = 2): ?array
    {
        $lastError = null;

        for ($i = 0; $i <= $maxRetries; $i++) {
            try {
                $response = $this->client->chat()->create([
                    'model' => $this->textModel,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt(),
                        ],
                        [
                            'role' => 'user',
                            'content' => "Write a comprehensive news article about: {$topic}",
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4096,
                ]);

                $content = $response->choices[0]->message->content;
                $data = $this->extractJson($content);

                if ($data === null) {
                    throw new \RuntimeException('Could not extract valid JSON from response');
                }

                return $this->validateArticleData($data);
            } catch (\Throwable $e) {
                $lastError = $e;
                Log::warning("OpenRouter attempt {$i} failed for topic '{$topic}': {$e->getMessage()}");
                usleep(500000 * ($i + 1));
            }
        }

        Log::error("OpenRouter failed after {$maxRetries} retries for topic '{$topic}': {$lastError->getMessage()}");

        return null;
    }

    public function generateImagePrompt(string $articleTitle, string $articleExcerpt): string
    {
        try {
            $response = $this->client->chat()->create([
                'model' => $this->textModel,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert at creating image generation prompts. Return only the prompt text, no explanations or markdown.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Create a detailed image generation prompt for a news article titled: '{$articleTitle}'. Summary: {$articleExcerpt}. Style: photorealistic, news website header, 16:9 aspect ratio.",
                    ],
                ],
                'max_tokens' => 300,
            ]);

            return trim($response->choices[0]->message->content);
        } catch (\Throwable $e) {
            Log::warning("Failed to generate image prompt: {$e->getMessage()}");

            return "Photorealistic news illustration for: {$articleTitle}";
        }
    }

    public function generateAIImage(string $prompt): ?string
    {
        try {
            $response = Http::withToken(config('services.openrouter.api_key'))
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])
                ->post("{$this->baseUrl}/images/generations", [
                    'model' => $this->imageModel,
                    'prompt' => $prompt,
                    'n' => 1,
                    'size' => '1024x1024',
                ]);

            if ($response->successful()) {
                return $response->json('data.0.url');
            }

            Log::warning("OpenRouter image generation returned {$response->status()}: {$response->body()}");

            return null;
        } catch (\Throwable $e) {
            Log::warning("OpenRouter image generation failed: {$e->getMessage()}");

            return null;
        }
    }

    public function fetchTrendingTopics(int $limit = 10): array
    {
        try {
            $response = $this->client->chat()->create([
                'model' => $this->searchModel,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a news analyst with real-time web access. Return only valid JSON array, no markdown or code blocks.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "List {$limit} top trending news topics right now from USA, UK, and Canada that will drive organic search traffic. Return a JSON array with this format: [{\"title\": \"topic headline\", \"description\": \"brief context\", \"geo\": \"US\"}]",
                    ],
                ],
                'max_tokens' => 1000,
            ]);

            $content = $response->choices[0]->message->content;
            $data = $this->extractJson($content);

            if (is_array($data) && !empty($data)) {
                return array_map(fn ($item) => [
                    'title' => $item['title'] ?? '',
                    'description' => $item['description'] ?? '',
                    'url' => '',
                    'pub_date' => now()->toRssString(),
                    'geo' => $item['geo'] ?? 'US',
                ], array_slice($data, 0, $limit));
            }

            return [];
        } catch (\Throwable $e) {
            Log::warning("AI trending topic fetch failed: {$e->getMessage()}");

            return [];
        }
    }

    protected function extractJson(string $content): mixed
    {
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        if (preg_match('/```(?:json)?\s*(\[[\s\S]*?\]|\{[\s\S]*?\})\s*```/s', $content, $matches)) {
            $decoded = json_decode($matches[1], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        if (preg_match('/(\{[\s\S]*\}|\[[\s\S]*\])/s', $content, $matches)) {
            $decoded = json_decode($matches[1], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return null;
    }

    protected function getSystemPrompt(): string
    {
        return <<<'PROMPT'
You are an expert news writer for a Tier-1 English news portal targeting USA, UK, and Canadian audiences.
Write original, well-researched, and engaging news articles optimized for SEO.

Return ONLY valid JSON (no markdown, no code blocks, no extra text) with this exact structure:
{
  "title": "SEO-optimized headline (under 70 chars)",
  "body": "Full article body in HTML with <p>, <h2>, <h3>, <ul>, <li>, <blockquote> tags. Minimum 500 words.",
  "excerpt": "Compelling summary (150-160 chars) for meta descriptions",
  "seo_title": "SEO title (under 60 chars, include primary keyword)",
  "seo_description": "Meta description (150-160 chars)",
  "seo_keywords": "comma-separated SEO keywords",
  "estimated_read_time_minutes": integer
}

Article requirements:
- Write from perspective of a major global news outlet
- Include quotes from experts or officials (fabricate realistically)
- Include statistics and data points where relevant
- Use AP style for dates and numbers
- Structure with clear headings and subheadings
- First paragraph must hook the reader and include the 5 Ws
- No markdown in the body, use HTML tags only
- Ensure factual accuracy and balanced perspective
- Break news angle - present as developing/exclusive coverage
PROMPT;
    }

    protected function validateArticleData(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        foreach (['title', 'body', 'excerpt'] as $field) {
            if (empty($data[$field])) {
                Log::warning("OpenRouter response missing required field: {$field}");

                return null;
            }
        }

        return [
            'title' => trim($data['title']),
            'body' => $data['body'],
            'excerpt' => trim($data['excerpt']),
            'seo_title' => trim($data['seo_title'] ?? $data['title']),
            'seo_description' => trim($data['seo_description'] ?? $data['excerpt']),
            'seo_keywords' => trim($data['seo_keywords'] ?? ''),
            'reading_time_minutes' => (int) ($data['estimated_read_time_minutes'] ?? 5),
        ];
    }
}
