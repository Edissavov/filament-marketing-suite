<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource;
use VasilGerginski\FilamentShortUrl\Filament\Resources\ShortUrlResource\Pages\EditShortUrl as BaseEditShortUrl;

class EditShortUrl extends BaseEditShortUrl
{
    protected static string $resource = ShortUrlResource::class;
}
