<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;

class PageViewsChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Traffic — Last 30 Days';

    protected ?string $pollingInterval = '300s';

    protected function getFilters(): ?array
    {
        return [
            'views'    => 'Page Views',
            'visitors' => 'Unique Visitors',
            'both'     => 'Both',
        ];
    }

    protected function getData(): array
    {
        $chart  = PageView::last30DaysChart();
        $filter = $this->filter ?? 'both';

        $datasets = [];

        if (in_array($filter, ['views', 'both'])) {
            $datasets[] = [
                'label'           => 'Page Views',
                'data'            => $chart['views'],
                'borderColor'     => '#3b82f6',
                'backgroundColor' => 'rgba(59,130,246,0.1)',
                'fill'            => true,
                'tension'         => 0.4,
                'pointRadius'     => 3,
            ];
        }

        if (in_array($filter, ['visitors', 'both'])) {
            $datasets[] = [
                'label'           => 'Unique Visitors',
                'data'            => $chart['visitors'],
                'borderColor'     => '#10b981',
                'backgroundColor' => 'rgba(16,185,129,0.1)',
                'fill'            => true,
                'tension'         => 0.4,
                'pointRadius'     => 3,
            ];
        }

        return [
            'labels'   => $chart['labels'],
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['precision' => 0],
                ],
            ],
        ];
    }
}
