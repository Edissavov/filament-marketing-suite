<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources;

use VasilGerginski\MarketingSuite\Filament\Components\TranslatableTabs;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpCategoryResource\Pages;
use VasilGerginski\MarketingSuite\Models\HelpCategory;

class HelpCategoryResource extends Resource
{
    protected static ?string $model = HelpCategory::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function getModelLabel(): string
    {
        return __('Help Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Help Categories');
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
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(static fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    ])
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('icon')
                    ->placeholder('tabler-help-circle')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label(__('Articles')),
                TextColumn::make('sort_order')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([])
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
            'index' => Pages\ListHelpCategories::route('/'),
            'create' => Pages\CreateHelpCategory::route('/create'),
            'edit' => Pages\EditHelpCategory::route('/{record}/edit'),
        ];
    }
}
