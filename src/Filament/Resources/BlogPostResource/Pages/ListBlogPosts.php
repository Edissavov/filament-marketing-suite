<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
