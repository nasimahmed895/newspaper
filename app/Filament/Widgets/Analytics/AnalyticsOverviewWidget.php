<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $todayViews      = PageView::todayViews();
        $todayVisitors   = PageView::todayUniqueVisitors();
        $weekViews       = PageView::weekViews();
        $weekVisitors    = PageView::weekUniqueVisitors();
        $activeNow       = PageView::activeNow();

        $avgPagesPerSession = $weekVisitors > 0
            ? round($weekViews / $weekVisitors, 1)
            : 0;

        // 7-day daily views trend for sparkline
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $trend[] = PageView::whereDate('created_at', now()->subDays($i))->count();
        }

        return [
            Stat::make('Active Right Now', $activeNow)
                ->description('Visitors in last 5 minutes')
                ->descriptionIcon(Heroicon::OutlinedSignal)
                ->descriptionColor('success')
                ->icon(Heroicon::OutlinedCursorArrowRays)
                ->color('success'),

            Stat::make("Today's Visitors", number_format($todayVisitors))
                ->description(number_format($todayViews) . ' total page views')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->descriptionColor('info')
                ->icon(Heroicon::OutlinedUserGroup)
                ->color('info')
                ->chart($trend),

            Stat::make("This Week's Visitors", number_format($weekVisitors))
                ->description(number_format($weekViews) . ' page views this week')
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->descriptionColor('primary')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('primary')
                ->chart($trend),

            Stat::make('Avg Pages / Session', $avgPagesPerSession)
                ->description('This week (higher = more engaged)')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->descriptionColor($avgPagesPerSession >= 2 ? 'success' : 'warning')
                ->icon(Heroicon::OutlinedDocumentDuplicate)
                ->color($avgPagesPerSession >= 2 ? 'success' : 'warning'),
        ];
    }
}
