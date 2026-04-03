<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\EventSubmissions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use VasilGerginski\MarketingSuite\Filament\Resources\EventSubmissions\EventSubmissionResource;

class ManageEventSubmissions extends ManageRecords
{
    protected static string $resource = EventSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
