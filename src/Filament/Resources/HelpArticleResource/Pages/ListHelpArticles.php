<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHelpArticles extends ListRecords
{
    protected static string $resource = HelpArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
