<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\Authors\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\AuthorResource;

class ListAuthors extends ListRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
