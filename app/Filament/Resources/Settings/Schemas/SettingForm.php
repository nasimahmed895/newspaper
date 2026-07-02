<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setting Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->regex('/^[a-z0-9_.]+$/')
                            ->helperText('Use dot notation: e.g., site.name, social.twitter'),
                        TextInput::make('label')
                            ->label('Display Label'),
                        Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'boolean' => 'Yes/No',
                                'select' => 'Select',
                                'image' => 'Image URL',
                                'color' => 'Color',
                            ])
                            ->default('text'),
                        Select::make('group')
                            ->options([
                                'general' => 'General',
                                'seo' => 'SEO',
                                'social' => 'Social Media',
                                'ads' => 'Advertising',
                                'api' => 'API Keys',
                                'automation' => 'Automation',
                            ])
                            ->default('general'),
                        Textarea::make('value')
                            ->label('Value')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('options')
                            ->label('Options (JSON)')
                            ->rows(2)
                            ->helperText('For select type: {"option1": "Label 1", "option2": "Label 2"}')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
