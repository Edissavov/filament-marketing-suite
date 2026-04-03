<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource;

class EditFaqItem extends EditRecord
{
    protected static string $resource = FaqItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
