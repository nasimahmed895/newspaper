<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleTrendsService
{
    protected const DAILY_TRENDS_URL = 'https://trends.google.com/trending/rss/daily?geo=';

    public function fetchTrendingTopics(string $geo = 'US', int $limit = 10): array
    {
        $url = self::DAILY_TRENDS_URL . $geo;

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    'Accept' => 'application/rss+xml, application/xml, text/xml',
                ])
                ->get($url);

            if (!$response->successful()) {
                Log::warning("Google Trends RSS returned status {$response->status()} for geo={$geo}");

                return $this->getFallbackTopics($limit);
            }

            $xml = simplexml_load_string($response->body());

            if (!$xml || !isset($xml->channel->item)) {
                Log::warning('Google Trends RSS returned no items');

                return $this->getFallbackTopics($limit);
            }

            $topics = [];
            $count = 0;

            foreach ($xml->channel->item as $item) {
                if ($count >= $limit) {
                    break;
                }

                $title = trim((string) $item->title);
                if (empty($title)) {
                    continue;
                }

                $topics[] = [
                    'title' => $title,
                    'description' => trim((string) $item->description),
                    'url' => trim((string) $item->link),
                    'pub_date' => trim((string) $item->pubDate),
                    'geo' => $geo,
                ];

                $count++;
            }

            return empty($topics) ? $this->getFallbackTopics($limit) : $topics;
        } catch (\Throwable $e) {
            Log::error("Google Trends fetch failed for geo={$geo}: {$e->getMessage()}");

            return $this->getFallbackTopics($limit);
        }
    }

    public function fetchMultiRegionTrends(int $limitPerRegion = 5): array
    {
        $regions = ['US', 'GB', 'CA'];
        $allTopics = [];

        foreach ($regions as $geo) {
            try {
                $topics = $this->fetchTrendingTopics($geo, $limitPerRegion);
                $allTopics = array_merge($allTopics, $topics);
            } catch (\Throwable $e) {
                Log::warning("Failed to fetch trends for {$geo}: {$e->getMessage()}");
            }
        }

        shuffle($allTopics);

        return $allTopics;
    }

    protected function getFallbackTopics(int $limit = 10): array
    {
        // Try AI-powered discovery first (uses SEARCH_MODEL = google/gemini-2.5-flash)
        try {
            $aiTopics = app(OpenRouterService::class)->fetchTrendingTopics($limit);
            if (!empty($aiTopics)) {
                Log::info('Using AI-powered trending topics as fallback');

                return $aiTopics;
            }
        } catch (\Throwable $e) {
            Log::warning("AI trending fallback failed: {$e->getMessage()}");
        }

        // Static fallback as last resort
        $fallbacks = [
            'Artificial Intelligence Breakthroughs Reshaping Industries Worldwide',
            'Global Economic Outlook: Markets React to Policy Changes',
            'Climate Summit 2026: World Leaders Commit to New Emission Targets',
            'Technology Giants Announce Major Product Innovations',
            'Healthcare Revolution: New Treatments Transforming Patient Care',
            'Space Exploration Achieves New Milestones',
            'International Trade Agreements Boost Global Economy',
            'Cybersecurity Threats: Protecting Digital Infrastructure',
            'Renewable Energy Adoption Accelerates Across Continents',
            'Education Technology Transforming Learning Experiences',
        ];

        return array_map(fn ($title) => [
            'title' => $title,
            'description' => "Latest developments in {$title}",
            'url' => '',
            'pub_date' => now()->toRssString(),
            'geo' => 'US',
        ], array_slice($fallbacks, 0, $limit));
    }
}
