<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\Authors\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\AuthorResource;

class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
