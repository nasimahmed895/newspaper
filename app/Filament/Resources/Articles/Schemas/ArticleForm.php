<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article Content')
                    ->description('Main article content and metadata')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($set, $state) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('author')
                            ->options([
                                'News Desk' => 'News Desk',
                                'Staff Reporter' => 'Staff Reporter',
                                'Editorial Team' => 'Editorial Team',
                                'Tech Correspondent' => 'Tech Correspondent',
                                'World Affairs Desk' => 'World Affairs Desk',
                            ])
                            ->default('News Desk'),
                        TextInput::make('source')
                            ->label('Source Name'),
                        TextInput::make('source_url')
                            ->label('Source URL')
                            ->url(),
                        TextInput::make('trending_topic')
                            ->label('Trending Topic'),
                    ]),
                Section::make('Body')
                    ->schema([
                        RichEditor::make('content')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('attachments')
                            ->columnSpanFull(),
                    ]),
                Section::make('SEO & Excerpt')
                    ->description('Search engine optimization fields')
                    ->columns(2)
                    ->schema([
                        Textarea::make('excerpt')
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Meta description fallback if SEO description is empty'),
                        TextInput::make('seo_title')
                            ->label('SEO Title')
                            ->maxLength(70)
                            ->helperText('Recommended: max 60 characters'),
                        Textarea::make('seo_description')
                            ->label('SEO Description')
                            ->rows(2)
                            ->maxLength(160)
                            ->helperText('Recommended: max 160 characters'),
                        Textarea::make('seo_keywords')
                            ->label('SEO Keywords')
                            ->rows(2)
                            ->helperText('Comma-separated keywords'),
                    ]),
                Section::make('Publishing')
                    ->description('Publish status and featured image')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->inline(false),
                        Toggle::make('is_trending')
                            ->label('Mark as Trending')
                            ->inline(false),
                        TextInput::make('reading_time_minutes')
                            ->label('Reading Time (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(60)
                            ->default(5),
                    ]),
            ]);
    }
}
