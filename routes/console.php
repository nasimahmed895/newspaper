<?php

use App\Console\Commands\GenerateNews;
use App\Console\Commands\SubmitToGoogleIndex;
use App\Console\Commands\UpdateSitemap;
use Illuminate\Support\Facades\Schedule;

// News generation: run hourly to keep content fresh
Schedule::command(GenerateNews::class)->hourly()->withoutOverlapping();

// Update sitemap after news generation
Schedule::command(UpdateSitemap::class)->hourlyAt(5)->withoutOverlapping();

// Submit new articles to Google Indexing API hourly
Schedule::command(SubmitToGoogleIndex::class)->hourlyAt(10)->withoutOverlapping();
