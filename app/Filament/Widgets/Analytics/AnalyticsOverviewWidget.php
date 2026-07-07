<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class AnalyticsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        try {
            return Schema::hasTable('page_views');
        } catch (\Throwable) {
            return false;
        }
    }

    protected function getStats(): array
    {
        if (!$this->tableReady()) {
            return $this->emptyStats();
        }

        try {
            $todayViews    = PageView::todayViews();
            $todayVisitors = PageView::todayUniqueVisitors();
            $weekViews     = PageView::weekViews();
            $weekVisitors  = PageView::weekUniqueVisitors();
            $activeNow     = PageView::activeNow();

            $avgPagesPerSession = $weekVisitors > 0
                ? round($weekViews / $weekVisitors, 1)
                : 0;

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
        } catch (\Throwable) {
            return $this->emptyStats();
        }
    }

    private function tableReady(): bool
    {
        try {
            return Schema::hasTable('page_views');
        } catch (\Throwable) {
            return false;
        }
    }

    private function emptyStats(): array
    {
        return [
            Stat::make('Active Right Now', 0)
                ->description('Run migration to enable tracking')
                ->descriptionIcon(Heroicon::OutlinedExclamationCircle)
                ->descriptionColor('warning')
                ->icon(Heroicon::OutlinedCursorArrowRays)
                ->color('gray'),

            Stat::make("Today's Visitors", 0)
                ->description('Awaiting migration')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->descriptionColor('gray')
                ->icon(Heroicon::OutlinedUserGroup)
                ->color('gray'),

            Stat::make("This Week's Visitors", 0)
                ->description('Awaiting migration')
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->descriptionColor('gray')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('gray'),

            Stat::make('Avg Pages / Session', 0)
                ->description('Awaiting migration')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->descriptionColor('gray')
                ->icon(Heroicon::OutlinedDocumentDuplicate)
                ->color('gray'),
        ];
    }
}
