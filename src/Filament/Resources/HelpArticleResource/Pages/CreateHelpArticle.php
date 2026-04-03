<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource;

class CreateHelpArticle extends CreateRecord
{
    protected static string $resource = HelpArticleResource::class;
}
