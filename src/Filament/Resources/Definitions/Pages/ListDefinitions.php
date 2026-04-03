<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\DefinitionResource;

class ListDefinitions extends ListRecords
{
    protected static string $resource = DefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
