<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;

class DeviceBreakdownWidget extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = [
        'sm' => 'full',
        'lg' => 1,
    ];

    protected ?string $heading = 'Devices';

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
        $data   = PageView::deviceBreakdown($period);

        return [
            'labels'   => ['Desktop', 'Mobile', 'Tablet'],
            'datasets' => [[
                'data'            => [
                    $data['desktop'] ?? 0,
                    $data['mobile']  ?? 0,
                    $data['tablet']  ?? 0,
                ],
                'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b'],
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
