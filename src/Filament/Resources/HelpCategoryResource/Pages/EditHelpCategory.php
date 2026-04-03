<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\HelpCategoryResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpCategoryResource;

class EditHelpCategory extends EditRecord
{
    protected static string $resource = HelpCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
