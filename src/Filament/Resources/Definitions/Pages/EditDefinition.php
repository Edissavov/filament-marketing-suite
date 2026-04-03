<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\DefinitionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDefinition extends EditRecord
{
    protected static string $resource = DefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
