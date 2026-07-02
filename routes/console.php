<?php

use App\Console\Commands\GenerateNews;
use App\Console\Commands\SubmitToGoogleIndex;
use App\Console\Commands\UpdateSitemap;
use Illuminate\Support\Facades\Schedule;

// 12 articles/day — 1 every 2 hours at :00
Schedule::command(GenerateNews::class)->cron('0 */2 * * *')->withoutOverlapping();

// Sitemap update 5 minutes after news generation
Schedule::command(UpdateSitemap::class)->cron('5 */2 * * *')->withoutOverlapping();

// Submit new articles to Google Indexing API 10 minutes after news generation
Schedule::command(SubmitToGoogleIndex::class)->cron('10 */2 * * *')->withoutOverlapping();
