<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\Authors;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\Pages\CreateAuthor;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\Pages\EditAuthor;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\Pages\ListAuthors;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\Schemas\AuthorForm;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\Tables\AuthorsTable;
use VasilGerginski\MarketingSuite\Models\Author;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUser;

    public static function getModelLabel(): string
    {
        return __('Author');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Authors');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Blog');
    }

    public static function form(Schema $schema): Schema
    {
        return AuthorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuthors::route('/'),
            'create' => CreateAuthor::route('/create'),
            'edit' => EditAuthor::route('/{record}/edit'),
        ];
    }
}
