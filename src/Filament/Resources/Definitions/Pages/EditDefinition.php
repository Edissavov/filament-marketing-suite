<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\DefinitionResource;

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
