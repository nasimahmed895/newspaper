<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Image')
                    ->disk(null)
                    ->getStateUsing(function ($record) {
                        $value = $record->featured_image;
                        if (!$value) return null;
                        if (filter_var($value, FILTER_VALIDATE_URL)) return $value;
                        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
                    })
                    ->width(80)
                    ->height(50)
                    ->extraImgAttributes(['style' => 'object-fit:cover;border-radius:4px;'])
                    ->toggleable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'published' => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('category.name')
                    ->badge()
                    ->sortable(),
                TextColumn::make('author')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('apiPartner.name')
                    ->label('Source Site')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('submitted_by_name')
                    ->label('Submitted By')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Live'),
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
                SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'published' => 'Published',
                        'rejected'  => 'Rejected',
                    ]),
                TernaryFilter::make('is_trending')
                    ->label('Trending'),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('api_partner_id')
                    ->label('Source Site')
                    ->relationship('apiPartner', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve & Publish Article')
                    ->modalDescription('This article will go live immediately.')
                    ->modalSubmitActionLabel('Yes, Publish')
                    ->action(fn (Article $record) => $record->update([
                        'status'       => 'published',
                        'is_published' => true,
                        'published_at' => now(),
                    ]))
                    ->visible(fn (Article $record): bool => $record->status === 'pending'),
                Action::make('reject')
                    ->label('Reject')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Article')
                    ->modalDescription('This article will be marked as rejected and stay unpublished.')
                    ->modalSubmitActionLabel('Yes, Reject')
                    ->action(fn (Article $record) => $record->update([
                        'status'       => 'rejected',
                        'is_published' => false,
                    ]))
                    ->visible(fn (Article $record): bool => $record->status === 'pending'),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('Approve & Publish')
                        ->icon(Heroicon::OutlinedCheck)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Approve & Publish Selected Articles')
                        ->modalDescription('All selected articles will go live immediately.')
                        ->modalSubmitActionLabel('Yes, Publish All')
                        ->action(function (Collection $records): void {
                            $records->each(fn (Article $record) => $record->update([
                                'status'       => 'published',
                                'is_published' => true,
                                'published_at' => now(),
                            ]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }
}
