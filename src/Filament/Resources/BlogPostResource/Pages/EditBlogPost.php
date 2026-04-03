<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
