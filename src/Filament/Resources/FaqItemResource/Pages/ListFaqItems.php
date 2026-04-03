<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource;

class ListFaqItems extends ListRecords
{
    protected static string $resource = FaqItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
