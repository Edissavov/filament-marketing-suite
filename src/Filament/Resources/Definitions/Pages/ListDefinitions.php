<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\DefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

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
