<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\HelpCategoryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpCategoryResource;

class ListHelpCategories extends ListRecords
{
    protected static string $resource = HelpCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
