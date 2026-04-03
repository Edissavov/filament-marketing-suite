<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\Definitions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages\CreateDefinition;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages\EditDefinition;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages\ListDefinitions;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Schemas\DefinitionForm;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Tables\DefinitionsTable;
use VasilGerginski\MarketingSuite\Models\Definition;

class DefinitionResource extends Resource
{
    protected static ?string $model = Definition::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function getModelLabel(): string
    {
        return __('Definition');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Definitions');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function form(Schema $schema): Schema
    {
        return DefinitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DefinitionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDefinitions::route('/'),
            'create' => CreateDefinition::route('/create'),
            'edit' => EditDefinition::route('/{record}/edit'),
        ];
    }
}
