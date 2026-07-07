<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'article_id',
        'session_id',
        'ip_hash',
        'page_type',
        'device_type',
        'browser',
        'os',
        'referrer_source',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public static function todayViews(): int
    {
        return static::whereDate('created_at', today())->count();
    }

    public static function todayUniqueVisitors(): int
    {
        return static::whereDate('created_at', today())
            ->distinct('ip_hash')
            ->count('ip_hash');
    }

    public static function activeNow(): int
    {
        return static::where('created_at', '>=', now()->subMinutes(5))
            ->distinct('session_id')
            ->count('session_id');
    }

    public static function weekViews(): int
    {
        return static::where('created_at', '>=', now()->startOfWeek())->count();
    }

    public static function weekUniqueVisitors(): int
    {
        return static::where('created_at', '>=', now()->startOfWeek())
            ->distinct('ip_hash')
            ->count('ip_hash');
    }

    public static function last30DaysChart(): array
    {
        $rows = static::selectRaw('DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT ip_hash) as visitors')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get()
            ->keyBy('date');

        $labels = [];
        $views = [];
        $visitors = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $views[] = $rows[$date]->views ?? 0;
            $visitors[] = $rows[$date]->visitors ?? 0;
        }

        return compact('labels', 'views', 'visitors');
    }

    public static function deviceBreakdown(string $period = 'today'): array
    {
        $query = static::selectRaw('device_type, COUNT(*) as count')->groupBy('device_type');
        static::applyPeriod($query, $period);
        return $query->pluck('count', 'device_type')->toArray();
    }

    public static function trafficSources(string $period = 'today'): array
    {
        $query = static::selectRaw('referrer_source, COUNT(*) as count')->groupBy('referrer_source');
        static::applyPeriod($query, $period);
        return $query->pluck('count', 'referrer_source')->toArray();
    }

    public static function topArticles(string $period = 'today', int $limit = 10): \Illuminate\Support\Collection
    {
        $query = static::selectRaw('article_id, COUNT(*) as views, COUNT(DISTINCT ip_hash) as unique_visitors')
            ->whereNotNull('article_id')
            ->groupBy('article_id')
            ->orderByDesc('views')
            ->limit($limit)
            ->with('article.category');
        static::applyPeriod($query, $period);
        return $query->get();
    }

    private static function applyPeriod(\Illuminate\Database\Eloquent\Builder $query, string $period): void
    {
        match ($period) {
            'today'   => $query->whereDate('created_at', today()),
            'week'    => $query->where('created_at', '>=', now()->startOfWeek()),
            'month'   => $query->where('created_at', '>=', now()->startOfMonth()),
            'all'     => null,
            default   => $query->whereDate('created_at', today()),
        };
    }
}
