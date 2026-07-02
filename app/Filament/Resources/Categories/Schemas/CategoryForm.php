<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($set, $state) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        ColorPicker::make('color')
                            ->label('Accent Color'),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
