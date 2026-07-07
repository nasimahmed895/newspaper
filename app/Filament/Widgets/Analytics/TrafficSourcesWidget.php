<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;

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
        $period = $this->filter ?? 'today';
        $data   = PageView::trafficSources($period);

        $labels = [
            'direct'  => 'Direct',
            'organic' => 'Organic Search',
            'social'  => 'Social Media',
            'referral' => 'Referral',
        ];

        $colors = [
            'direct'   => '#6366f1',
            'organic'  => '#10b981',
            'social'   => '#f59e0b',
            'referral' => '#3b82f6',
        ];

        $keys   = array_keys($labels);
        $counts = array_map(fn ($k) => $data[$k] ?? 0, $keys);

        return [
            'labels'   => array_values($labels),
            'datasets' => [[
                'data'            => $counts,
                'backgroundColor' => array_values($colors),
                'hoverOffset'     => 6,
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
            ],
            'cutout' => '65%',
        ];
    }
}
