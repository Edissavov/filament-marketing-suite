<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\EventResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\EventResource;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
