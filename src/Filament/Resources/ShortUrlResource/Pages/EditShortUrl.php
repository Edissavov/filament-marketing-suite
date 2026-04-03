<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;

use VasilGerginski\FilamentShortUrl\Filament\Resources\ShortUrlResource\Pages\EditShortUrl as BaseEditShortUrl;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource;

class EditShortUrl extends BaseEditShortUrl
{
    protected static string $resource = ShortUrlResource::class;
}
