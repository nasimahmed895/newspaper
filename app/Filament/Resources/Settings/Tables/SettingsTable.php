<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('group')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('label')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('value')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Group')
                    ->options([
                        'general' => 'General',
                        'seo' => 'SEO',
                        'social' => 'Social Media',
                        'ads' => 'Advertising',
                        'api' => 'API Keys',
                        'automation' => 'Automation',
                    ]),
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'boolean' => 'Yes/No',
                        'select' => 'Select',
                        'image' => 'Image URL',
                        'color' => 'Color',
                    ]),
            ])
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
