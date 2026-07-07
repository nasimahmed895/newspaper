<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class TrafficSourcesWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'sm' => 'full',
        'lg' => 1,
    ];

    protected ?string $heading = 'Traffic Sources';

    protected ?string $pollingInterval = '120s';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week'  => 'This Week',
            'month' => 'This Month',
        ];
    }

    protected function getData(): array
    {
        $labels = ['Direct', 'Organic Search', 'Social Media', 'Referral'];
        $colors = ['#6366f1', '#10b981', '#f59e0b', '#3b82f6'];
        $empty  = ['labels' => $labels, 'datasets' => [['data' => [0, 0, 0, 0], 'backgroundColor' => $colors, 'hoverOffset' => 6]]];

        try {
            if (!Schema::hasTable('page_views')) return $empty;

            $data   = PageView::trafficSources($this->filter ?? 'today');
            $counts = [
                $data['direct']   ?? 0,
                $data['organic']  ?? 0,
                $data['social']   ?? 0,
                $data['referral'] ?? 0,
            ];

            return ['labels' => $labels, 'datasets' => [['data' => $counts, 'backgroundColor' => $colors, 'hoverOffset' => 6]]];
        } catch (\Throwable) {
            return $empty;
        }
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['position' => 'bottom']],
            'cutout'  => '65%',
        ];
    }
}
