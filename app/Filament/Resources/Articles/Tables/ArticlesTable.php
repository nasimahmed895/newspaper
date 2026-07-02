<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('category.name')
                    ->badge()
                    ->sortable(),
                TextColumn::make('author')
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                TextColumn::make('published_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('view_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_trending')
                    ->boolean()
                    ->label('Trending'),
                TextColumn::make('reading_time_minutes')
                    ->label('Read Time')
                    ->suffix(' min')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published Status'),
                TernaryFilter::make('is_trending')
                    ->label('Trending'),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }
}
