<?php

namespace App\Filament\Resources\AdPlacements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdPlacementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ad Placement Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('location')
                            ->required()
                            ->options([
                                'sidebar-top' => 'Sidebar - Top',
                                'sidebar-bottom' => 'Sidebar - Bottom',
                                'header' => 'Header',
                                'footer' => 'Footer',
                                'in-article' => 'In Article',
                                'between-articles' => 'Between Articles',
                                'above-content' => 'Above Content',
                                'below-content' => 'Below Content',
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),
                        Textarea::make('code')
                            ->label('Ad Code (HTML/JS)')
                            ->rows(5)
                            ->helperText('Paste your ad network code here')
                            ->columnSpanFull(),
                        TextInput::make('image_url')
                            ->label('Image URL')
                            ->url()
                            ->columnSpanFull(),
                        TextInput::make('link_url')
                            ->label('Click-through URL')
                            ->url()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
