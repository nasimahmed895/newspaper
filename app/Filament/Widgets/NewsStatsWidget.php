<?php

namespace App\Filament\Widgets;

use App\Models\ApiPartner;
use App\Models\Article;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewsStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $pending      = Article::where('status', 'pending')->count();
        $publishedToday = Article::where('status', 'published')
            ->whereDate('published_at', today())
            ->count();
        $totalPublished = Article::where('status', 'published')->count();
        $partnerCount   = ApiPartner::where('is_active', true)->count();

        return [
            Stat::make('Pending Review', $pending)
                ->description($pending > 0 ? 'Articles awaiting your approval' : 'Queue is clear')
                ->descriptionIcon(
                    $pending > 0 ? Heroicon::OutlinedClock : Heroicon::OutlinedCheckCircle
                )
                ->descriptionColor($pending > 0 ? 'warning' : 'success')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color($pending > 0 ? 'warning' : 'success')
                ->url('/admin/articles?activeTab=pending'),

            Stat::make('Published Today', $publishedToday)
                ->description('Articles published in last 24h')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->descriptionColor('success')
                ->icon(Heroicon::OutlinedNewspaper)
                ->color('success')
                ->url('/admin/articles?activeTab=published'),

            Stat::make('Total Published', $totalPublished)
                ->description('All live articles on site')
                ->descriptionIcon(Heroicon::OutlinedGlobeAlt)
                ->descriptionColor('info')
                ->icon(Heroicon::OutlinedArchiveBox)
                ->color('info')
                ->url('/admin/articles?activeTab=all'),

            Stat::make('Active Partners', $partnerCount)
                ->description('Websites connected via API')
                ->descriptionIcon(Heroicon::OutlinedLink)
                ->descriptionColor('gray')
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->color('gray')
                ->url('/admin/api-partners'),
        ];
    }
}
