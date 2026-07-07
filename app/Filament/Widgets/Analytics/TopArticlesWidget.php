<?php

namespace App\Filament\Widgets\Analytics;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class TopArticlesWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '120s';

    public string $period = 'today';

    public static function canView(): bool
    {
        try {
            return Schema::hasTable('page_views');
        } catch (\Throwable) {
            return false;
        }
    }

    public function getTableHeading(): string
    {
        return 'Top Articles — ' . match ($this->period) {
            'today' => 'Today',
            'week'  => 'This Week',
            'month' => 'This Month',
            default => 'Today',
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\PageView::query()
                    ->selectRaw('article_id, COUNT(*) as views, COUNT(DISTINCT ip_hash) as unique_visitors')
                    ->whereNotNull('article_id')
                    ->when($this->period === 'today', fn ($q) => $q->whereDate('created_at', today()))
                    ->when($this->period === 'week',  fn ($q) => $q->where('created_at', '>=', now()->startOfWeek()))
                    ->when($this->period === 'month', fn ($q) => $q->where('created_at', '>=', now()->startOfMonth()))
                    ->groupBy('article_id')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('article.title')
                    ->label('Article')
                    ->limit(60)
                    ->searchable(query: fn (Builder $query, string $search) => $query)
                    ->url(fn ($record) => $record->article ? route('articles.show', $record->article->slug) : null)
                    ->openUrlInNewTab(),
                TextColumn::make('article.category.name')
                    ->label('Category')
                    ->badge(),
                TextColumn::make('views')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('unique_visitors')
                    ->label('Unique')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('today')
                    ->label('Today')
                    ->color($this->period === 'today' ? 'primary' : 'gray')
                    ->action(fn () => $this->period = 'today'),
                \Filament\Actions\Action::make('week')
                    ->label('Week')
                    ->color($this->period === 'week' ? 'primary' : 'gray')
                    ->action(fn () => $this->period = 'week'),
                \Filament\Actions\Action::make('month')
                    ->label('Month')
                    ->color($this->period === 'month' ? 'primary' : 'gray')
                    ->action(fn () => $this->period = 'month'),
            ])
            ->emptyStateHeading('No data yet')
            ->emptyStateDescription('Visit some articles to populate this.')
            ->paginated(false)
            ->striped();
    }
}
