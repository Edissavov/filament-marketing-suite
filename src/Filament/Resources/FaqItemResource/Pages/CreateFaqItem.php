<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqItem extends CreateRecord
{
    protected static string $resource = FaqItemResource::class;
}
