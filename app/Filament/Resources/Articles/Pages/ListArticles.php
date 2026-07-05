<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'pending' => Tab::make('Pending Review')
                ->badge(Article::where('status', 'pending')->count())
                ->query(fn ($query) => $query->where('status', 'pending')),

            'published' => Tab::make('Published')
                ->query(fn ($query) => $query->where('status', 'published')),

            'rejected' => Tab::make('Rejected')
                ->query(fn ($query) => $query->where('status', 'rejected')),

            'all' => Tab::make('All Articles')
                ->query(fn ($query) => $query),
        ];
    }
}
