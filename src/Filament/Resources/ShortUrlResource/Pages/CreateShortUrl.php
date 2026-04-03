<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;

use VasilGerginski\FilamentShortUrl\Filament\Resources\ShortUrlResource\Pages\CreateShortUrl as BaseCreateShortUrl;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource;

class CreateShortUrl extends BaseCreateShortUrl
{
    protected static string $resource = ShortUrlResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['track_visits'] ??= true;
        $data['track_ip_address'] ??= true;
        $data['track_browser'] ??= true;
        $data['track_browser_version'] ??= true;
        $data['track_operating_system'] ??= true;
        $data['track_operating_system_version'] ??= true;
        $data['track_referer_url'] ??= true;
        $data['track_device_type'] ??= true;
        $data['forward_query_params'] ??= true;

        return $data;
    }
}
