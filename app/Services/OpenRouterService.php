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
        $persona   = $this->getPersonaForTopic($topic);
        $lastError = null;

        for ($i = 0; $i <= $maxRetries; $i++) {
            try {
                $response = $this->client->chat()->create([
                    'model'    => $this->textModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $this->getSystemPrompt($persona)],
                        ['role' => 'user',   'content' => "Write a news article about: {$topic}"],
                    ],
                    'temperature' => 0.85,
                    'max_tokens'  => 4096,
                ]);

                $content = $response->choices[0]->message->content;
                $data    = $this->extractJson($content);

                if ($data === null) {
                    throw new \RuntimeException('Could not extract valid JSON from response');
                }

                $validated = $this->validateArticleData($data, $persona);

                if ($validated && !empty($validated['body'])) {
                    $validated['body'] = $this->humanizeArticle($validated['body'], $persona);
                }

                return $validated;
            } catch (\Throwable $e) {
                $lastError = $e;
                Log::warning("OpenRouter attempt {$i} failed for topic '{$topic}': {$e->getMessage()}");
                usleep(500000 * ($i + 1));
            }
        }

        Log::error("OpenRouter failed after {$maxRetries} retries for topic '{$topic}': {$lastError->getMessage()}");

        return null;
    }

    protected function getPersonaForTopic(string $topic): array
    {
        $topic = strtolower($topic);

        $personas = [
            'tech' => [
                'name'       => 'Sarah Chen',
                'title'      => 'Senior Technology Correspondent',
                'background' => '14 years covering Silicon Valley, AI, and consumer tech for Reuters and Wired. MIT Computer Science graduate.',
                'voice'      => 'analytical, skeptical of hype, explains complex tech in plain English, occasionally sarcastic about Big Tech',
                'beats'      => ['ai', 'artificial intelligence', 'tech', 'apple', 'google', 'microsoft', 'meta', 'software', 'app', 'startup', 'silicon', 'cyber', 'hack', 'data', 'robot', 'openai', 'nvidia', 'chip', 'semiconductor', 'electric vehicle', 'ev', 'tesla'],
            ],
            'sports' => [
                'name'       => 'Marcus Webb',
                'title'      => 'Sports Editor',
                'background' => '18 years covering NFL, NBA, Premier League, and Olympics. Former college athlete, ESPN contributor.',
                'voice'      => 'energetic, fan-perspective, uses sports jargon naturally, gets emotional about upsets, respects athletes',
                'beats'      => ['nfl', 'nba', 'mlb', 'nhl', 'soccer', 'football', 'basketball', 'baseball', 'hockey', 'olympic', 'sport', 'game', 'match', 'player', 'team', 'coach', 'league', 'championship', 'tournament', 'tennis', 'golf', 'ufc', 'mma', 'boxing'],
            ],
            'politics' => [
                'name'       => 'Diana Holloway',
                'title'      => 'Political Correspondent',
                'background' => '20 years in Washington D.C. covering Congress, White House, and international affairs for AP and The Hill.',
                'voice'      => 'neutral, policy-focused, cites sources precisely, connects dots between events, never editorializes party preference',
                'beats'      => ['president', 'congress', 'senate', 'white house', 'election', 'democrat', 'republican', 'political', 'vote', 'law', 'bill', 'policy', 'government', 'prime minister', 'parliament', 'supreme court', 'trump', 'biden', 'nato', 'sanction', 'diplomacy', 'war', 'ukraine', 'russia', 'china'],
            ],
            'business' => [
                'name'       => 'James Okafor',
                'title'      => 'Business & Finance Editor',
                'background' => 'Former Goldman Sachs analyst, 11 years covering markets, M&A, and global economy for Bloomberg and FT.',
                'voice'      => 'precise with numbers, connects market moves to real-world impact, speaks plainly about Wall Street complexity',
                'beats'      => ['stock', 'market', 'economy', 'inflation', 'fed', 'bank', 'finance', 'investment', 'merger', 'acquisition', 'ipo', 'earnings', 'revenue', 'gdp', 'interest rate', 'recession', 'crypto', 'bitcoin', 'dollar', 'trade', 'tariff', 'oil', 'energy'],
            ],
            'health' => [
                'name'       => 'Dr. Priya Nair',
                'title'      => 'Health & Science Correspondent',
                'background' => 'MD from Johns Hopkins, 9 years translating medical research into accessible journalism for NYT Health and WebMD.',
                'voice'      => 'evidence-based, cautious about overblown claims, empathetic to patients, cites peer-reviewed studies',
                'beats'      => ['health', 'medical', 'disease', 'virus', 'vaccine', 'cancer', 'drug', 'fda', 'hospital', 'doctor', 'study', 'research', 'mental health', 'obesity', 'diabetes', 'covid', 'flu', 'medication', 'clinical trial', 'science', 'climate'],
            ],
            'entertainment' => [
                'name'       => 'Zoe Marcus',
                'title'      => 'Culture & Entertainment Reporter',
                'background' => '8 years covering Hollywood, music, streaming, and celebrity culture for Variety and Rolling Stone.',
                'voice'      => 'conversational, pop-culture fluent, not afraid of strong opinions, connects entertainment to broader cultural moments',
                'beats'      => ['movie', 'film', 'music', 'album', 'celebrity', 'actor', 'singer', 'netflix', 'disney', 'streaming', 'award', 'oscar', 'grammy', 'emmy', 'tv', 'show', 'series', 'concert', 'tour', 'fashion', 'social media', 'tiktok', 'youtube', 'influencer'],
            ],
        ];

        foreach ($personas as $key => $persona) {
            foreach ($persona['beats'] as $keyword) {
                if (str_contains($topic, $keyword)) {
                    return array_merge($persona, ['niche' => $key]);
                }
            }
        }

        return array_merge($personas['business'], [
            'name'       => 'Alex Morgan',
            'title'      => 'Senior News Correspondent',
            'background' => '15 years of general assignment reporting across US, UK, and Canada for major wire services.',
            'voice'      => 'authoritative but accessible, strong narrative instinct, humanizes big stories through individual impact',
            'niche'      => 'general',
        ]);
    }

    protected function humanizeArticle(string $body, array $persona): string
    {
        try {
            $response = $this->client->chat()->create([
                'model'    => $this->textModel,
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => "You are {$persona['name']}, {$persona['title']}. Edit the following article draft to sound exactly like your authentic voice. Your voice: {$persona['voice']}. Rules: vary sentence length aggressively (mix 5-word punchy sentences with 25-word analytical ones), add 1 strong personal observation or opinion framed as analysis, use contractions naturally, fix any robotic transitions, keep all facts and HTML tags intact, never add new sections or headings.",
                    ],
                    [
                        'role'    => 'user',
                        'content' => "Edit this article draft to sound more like my authentic voice. Return only the edited HTML body, no JSON wrapper:\n\n{$body}",
                    ],
                ],
                'temperature' => 0.75,
                'max_tokens'  => 4096,
            ]);

            $humanized = trim($response->choices[0]->message->content ?? '');

            return !empty($humanized) ? $humanized : $body;
        } catch (\Throwable $e) {
            Log::warning("Humanize pass failed, using original body: {$e->getMessage()}");
            return $body;
        }
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

    public function fetchTopicForCategory(string $categoryName, string $categoryDescription): string
    {
        try {
            $response = $this->client->chat()->create([
                'model'    => $this->searchModel,
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a news editor with real-time web access. Return only the trending topic headline — no explanation, no JSON, just the headline text.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => "What is ONE specific trending news topic right now in the category \"{$categoryName}\" ({$categoryDescription})? It must be a real, searchable, breaking or developing story from the last 24 hours in USA, UK, or Canada. Return just the topic headline.",
                    ],
                ],
                'max_tokens' => 100,
            ]);

            $topic = trim($response->choices[0]->message->content ?? '');

            return !empty($topic) ? $topic : "Latest {$categoryName} news today";
        } catch (\Throwable $e) {
            Log::warning("fetchTopicForCategory failed for '{$categoryName}': {$e->getMessage()}");

            return "Latest {$categoryName} news today";
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

    protected function getSystemPrompt(array $persona): string
    {
        $name       = $persona['name'];
        $title      = $persona['title'];
        $background = $persona['background'];
        $voice      = $persona['voice'];

        return <<<PROMPT
You are {$name}, {$title}. Background: {$background}.

Your writing voice: {$voice}.

STRICT BANNED WORDS — never use these (they reveal AI authorship):
delve, moreover, furthermore, it's worth noting, in conclusion, navigate, tapestry, multifaceted, vibrant, realm, showcase, leverage, pivotal, crucial, underscore, embark, foster, robust, revolutionary, groundbreaking, game-changing, in today's world, it is important to note, needless to say, at the end of the day, moving forward, in light of, shed light on, stands as, serves as a reminder

WRITING RULES:
- Vary sentence length aggressively: mix 5-word punchy sentences with 25-word analytical ones
- Use contractions naturally (don't, it's, we're, they've)
- First paragraph: hook in one punchy sentence, then 5 Ws
- Add 1-2 direct quotes from named sources (realistic attribution)
- Include 2-3 real-sounding statistics with source attribution
- Add one paragraph with your personal analytical take framed as expert observation
- Use rhetorical questions once where natural
- AP style for numbers and dates
- HTML only in body (<p>, <h2>, <h3>, <ul>, <li>, <blockquote>) — no markdown

Return ONLY valid JSON, no markdown, no code blocks:
{
  "title": "Headline under 70 chars — direct, active voice, no clickbait",
  "body": "Full article HTML, minimum 600 words",
  "excerpt": "150-160 char summary for meta description",
  "seo_title": "SEO title under 60 chars with primary keyword",
  "seo_description": "Meta description 150-160 chars",
  "seo_keywords": "comma-separated keywords",
  "estimated_read_time_minutes": integer,
  "author": "{$name}"
}
PROMPT;
    }

    protected function validateArticleData(?array $data, array $persona = []): ?array
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
            'title'                 => trim($data['title']),
            'body'                  => $data['body'],
            'excerpt'               => trim($data['excerpt']),
            'seo_title'             => trim($data['seo_title'] ?? $data['title']),
            'seo_description'       => trim($data['seo_description'] ?? $data['excerpt']),
            'seo_keywords'          => trim($data['seo_keywords'] ?? ''),
            'reading_time_minutes'  => (int) ($data['estimated_read_time_minutes'] ?? 5),
            'author'                => $data['author'] ?? $persona['name'] ?? 'News Desk',
        ];
    }
}
