<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource;

class CreateFaqItem extends CreateRecord
{
    protected static string $resource = FaqItemResource::class;
}
