<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources;

use VasilGerginski\MarketingSuite\Filament\Components\TranslatableTabs;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource\Pages;
use VasilGerginski\MarketingSuite\Models\BlogPost;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function getModelLabel(): string
    {
        return __('Blog Post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Blog Posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Blog');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('translations')
                    ->locales(['bg', 'en'])
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(static function (string $operation, string $state, Set $set): void {
                                if ($operation !== 'create') {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('meta_title')
                            ->label(__('SEO Title'))
                            ->maxLength(255)
                            ->placeholder(__('Leave empty to use the post title'))
                            ->columnSpanFull(),
                        TextInput::make('meta_description')
                            ->label(__('SEO Description'))
                            ->maxLength(500)
                            ->placeholder(__('Leave empty to auto-generate from content'))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->nullable()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('category')
                    ->nullable()
                    ->options([
                        'financial' => 'Financial',
                        'other' => 'Other',
                        'global' => 'Global',
                        'authorial' => 'Authorial',
                    ]),
                FileUpload::make('image')
                    ->nullable()
                    ->image()
                    ->disk(config('marketing-suite.uploads.disk', 'public'))
                    ->directory('images')
                    ->visibility('public'),
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->nullable(),
                Toggle::make('is_published'),
                DateTimePicker::make('published_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('author.name')
                    ->label(__('Author')),
                IconColumn::make('is_published')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published'),
            ])
            ->recordActions([
                Action::make('togglePublish')
                    ->label(static fn (BlogPost $record): string => $record->is_published
                        ? __('Unpublish')
                        : __('Publish'))
                    ->icon(static fn (BlogPost $record): string => $record->is_published
                        ? 'heroicon-o-eye-slash'
                        : 'heroicon-o-eye')
                    ->action(static function (BlogPost $record): void {
                        $record->is_published = ! $record->is_published;
                        $record->published_at = $record->is_published ? now()->toImmutable() : null;
                        $record->save();
                    }),
            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
