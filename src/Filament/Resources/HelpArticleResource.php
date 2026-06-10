<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources;

use VasilGerginski\MarketingSuite\Filament\Components\TranslatableTabs;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource\Pages;
use VasilGerginski\MarketingSuite\Models\HelpArticle;
use VasilGerginski\MarketingSuite\Models\HelpCategory;

class HelpArticleResource extends Resource
{
    protected static ?string $model = HelpArticle::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function getModelLabel(): string
    {
        return __('Help Article');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Help Articles');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
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
                            ->afterStateUpdated(static fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Select::make('help_category_id')
                    ->label(__('Category'))
                    ->options(static fn () => HelpCategory::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                TernaryFilter::make('is_published'),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHelpArticles::route('/'),
            'create' => Pages\CreateHelpArticle::route('/create'),
            'edit' => Pages\EditHelpArticle::route('/{record}/edit'),
        ];
    }
}
