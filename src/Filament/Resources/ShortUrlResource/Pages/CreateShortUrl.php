<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;

use AshAllenDesign\ShortURL\Classes\KeyGenerator;
use Illuminate\Database\Eloquent\Model;
use VasilGerginski\FilamentShortUrl\Filament\Resources\ShortUrlResource\Pages\CreateShortUrl as BaseCreateShortUrl;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource;
use VasilGerginski\MarketingSuite\Models\ShortUrl;

class CreateShortUrl extends BaseCreateShortUrl
{
    protected static string $resource = ShortUrlResource::class;

    /**
     * The base page builds the record from form fields this resource does not
     * have (activated_at/deactivated_at) and drops the marketing fields, so
     * create the record explicitly from our own form data.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $key = app(KeyGenerator::class)->generateRandom();

        return ShortUrl::query()->create([
            'destination_url' => $data['destination_url'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'currency' => $data['currency'] ?? null,
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
            'url_key' => $key,
            'default_short_url' => ShortUrl::defaultShortUrlFor($key),
            'single_use' => false,
            'forward_query_params' => true,
            'track_visits' => true,
            'track_ip_address' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
            'activated_at' => now(),
        ]);
    }
}
