<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHelpArticle extends CreateRecord
{
    protected static string $resource = HelpArticleResource::class;
}
