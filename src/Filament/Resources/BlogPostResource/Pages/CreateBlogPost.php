<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;
}
