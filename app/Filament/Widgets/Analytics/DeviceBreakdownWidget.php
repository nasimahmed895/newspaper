<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class DeviceBreakdownWidget extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = [
        'sm' => 'full',
        'lg' => 1,
    ];

    protected ?string $heading = 'Devices';

    protected ?string $pollingInterval = '120s';

    public static function canView(): bool
    {
        try { return Schema::hasTable('page_views'); } catch (\Throwable) { return false; }
    }

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
        $labels = ['Desktop', 'Mobile', 'Tablet'];
        $colors = ['#3b82f6', '#10b981', '#f59e0b'];
        $empty  = ['labels' => $labels, 'datasets' => [['data' => [0, 0, 0], 'backgroundColor' => $colors, 'hoverOffset' => 6]]];

        try {
            if (!Schema::hasTable('page_views')) return $empty;

            $data = PageView::deviceBreakdown($this->filter ?? 'today');

            return [
                'labels'   => $labels,
                'datasets' => [[
                    'data'            => [$data['desktop'] ?? 0, $data['mobile'] ?? 0, $data['tablet'] ?? 0],
                    'backgroundColor' => $colors,
                    'hoverOffset'     => 6,
                ]],
            ];
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
