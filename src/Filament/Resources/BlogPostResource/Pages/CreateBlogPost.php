<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;
}
