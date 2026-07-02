<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenAI;

class OpenAIService
{
    protected \OpenAI\Client $client;

    protected string $model;

    public function __construct()
    {
        $model = config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o'));

        $this->client = OpenAI::client(config('services.openai.api_key'));
        $this->model = $model;
    }

    public function generateArticle(string $topic, int $maxRetries = 2): ?array
    {
        $lastError = null;

        for ($i = 0; $i <= $maxRetries; $i++) {
            try {
                $response = $this->client->chat()->create([
                    'model' => $this->model,
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
                    'response_format' => ['type' => 'json_object'],
                ]);

                $content = $response->choices[0]->message->content;
                $data = json_decode($content, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \RuntimeException('Invalid JSON response from OpenAI: ' . json_last_error_msg());
                }

                return $this->validateArticleData($data);
            } catch (\Throwable $e) {
                $lastError = $e;
                Log::warning("OpenAI attempt {$i} failed for topic '{$topic}': {$e->getMessage()}");
                usleep(500000 * ($i + 1));
            }
        }

        Log::error("OpenAI failed after {$maxRetries} retries for topic '{$topic}': {$lastError->getMessage()}");

        return null;
    }

    public function generateImagePrompt(string $articleTitle, string $articleExcerpt): string
    {
        try {
            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert at creating DALL-E image generation prompts. Return only the prompt text, no explanations.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Create a detailed DALL-E 3 image generation prompt for a news article titled: '{$articleTitle}'. Article summary: {$articleExcerpt}. Style: photorealistic, suitable for a news website header image, 16:9 aspect ratio.",
                    ],
                ],
                'max_tokens' => 300,
            ]);

            return trim($response->choices[0]->message->content);
        } catch (\Throwable $e) {
            Log::warning("Failed to generate image prompt: {$e->getMessage()}");
            return "News illustration for: {$articleTitle}";
        }
    }

    public function generateDalleImage(string $prompt): ?string
    {
        try {
            $response = $this->client->images()->create([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1792x1024',
                'quality' => 'standard',
            ]);

            return $response->data[0]->url;
        } catch (\Throwable $e) {
            Log::warning("DALL-E image generation failed: {$e->getMessage()}");
            return null;
        }
    }

    protected function getSystemPrompt(): string
    {
        return <<<'PROMPT'
You are an expert news writer for a Tier-1 English news portal targeting USA, UK, and Canadian audiences.
Write original, well-researched, and engaging news articles optimized for SEO.

Return ONLY valid JSON with this exact structure:
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

        $required = ['title', 'body', 'excerpt'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                Log::warning("OpenAI response missing required field: {$field}");
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
