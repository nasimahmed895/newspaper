<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@suport.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456789'),
            ]
        );

        // Seed categories
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Latest in tech, gadgets, and innovation', 'color' => '#3B82F6', 'order' => 1],
            ['name' => 'World News', 'slug' => 'world-news', 'description' => 'Global events and international affairs', 'color' => '#EF4444', 'order' => 2],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Markets, finance, and economic news', 'color' => '#10B981', 'order' => 3],
            ['name' => 'Science', 'slug' => 'science', 'description' => 'Scientific discoveries and research', 'color' => '#8B5CF6', 'order' => 4],
            ['name' => 'Health', 'slug' => 'health', 'description' => 'Healthcare, wellness, and medical news', 'color' => '#F59E0B', 'order' => 5],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports coverage and athletics', 'color' => '#06B6D4', 'order' => 6],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Movies, music, and pop culture', 'color' => '#EC4899', 'order' => 7],
            ['name' => 'Politics', 'slug' => 'politics', 'description' => 'Political news and analysis', 'color' => '#DC2626', 'order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Seed settings
        $settings = [
            ['key' => 'site.name', 'value' => 'WorldPulse24', 'group' => 'general', 'label' => 'Site Name', 'type' => 'text'],
            ['key' => 'site.description', 'value' => 'Independent AI-powered global news network delivering breaking news, analysis, and in-depth reporting.', 'group' => 'general', 'label' => 'Site Description', 'type' => 'text'],
            ['key' => 'seo.default_title', 'value' => 'WorldPulse24 — Breaking News, World Events & Analysis', 'group' => 'seo', 'label' => 'Default SEO Title', 'type' => 'text'],
            ['key' => 'seo.default_description', 'value' => 'WorldPulse24 delivers trusted breaking news, in-depth analysis, and real-time reporting on world events, politics, business, technology, science, health, sports, and entertainment.', 'group' => 'seo', 'label' => 'Default SEO Description', 'type' => 'text'],
            ['key' => 'automation.auto_publish', 'value' => 'true', 'group' => 'automation', 'label' => 'Auto-publish Generated Articles', 'type' => 'boolean'],
            ['key' => 'automation.max_articles', 'value' => '5', 'group' => 'automation', 'label' => 'Max Articles Per Generation', 'type' => 'text'],
            ['key' => 'automation.ai_model', 'value' => 'gpt-4o', 'group' => 'automation', 'label' => 'AI Model', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // Seed ad placements with actual content
        $this->call(AdPlacementSeeder::class);

        $this->call(ArticleSeeder::class);
        $this->call(TrendingNewsSeeder::class);

        $this->command->info('Database seeded successfully!');
    }
}
