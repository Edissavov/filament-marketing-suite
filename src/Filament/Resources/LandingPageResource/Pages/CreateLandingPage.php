<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource\Pages;

use AshAllenDesign\ShortURL\Classes\KeyGenerator;
use Filament\Resources\Pages\CreateRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Services\MailerLiteService;

class CreateLandingPage extends CreateRecord
{
    protected static string $resource = LandingPageResource::class;

    protected function afterCreate(): void
    {
        /** @var LandingPage $record */
        $record = $this->record;

        // Auto-create ShortURL for tracking
        $key = app(KeyGenerator::class)->generateRandom();

        ShortUrl::query()->firstOrCreate(
            ['destination_url' => $record->url],
            [
                'url_key' => $key,
                'default_short_url' => ShortUrl::defaultShortUrlFor($key),
                'description' => $record->title,
                'track_visits' => true,
                'track_ip_address' => true,
                'track_operating_system' => true,
                'track_operating_system_version' => true,
                'track_browser' => true,
                'track_browser_version' => true,
                'track_referer_url' => true,
                'track_device_type' => true,
                'single_use' => false,
                'is_active' => true,
            ],
        );

        // Auto-create MailerLite group
        if (! $record->mailerlite_group_id) {
            $groupId = app(MailerLiteService::class)->createGroup($record->title);

            if ($groupId) {
                $record->update(['mailerlite_group_id' => $groupId]);
            }
        }
    }
}
