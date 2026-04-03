<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_name;

    public ?string $tagline;

    public ?string $contact_email;

    public ?string $contact_phone;

    public ?string $facebook_url;

    public ?string $instagram_url;

    public ?string $linkedin_url;

    public ?string $youtube_url;

    public ?string $viber_url;

    public ?string $facebook_group_url;

    public ?string $facebook_pixel_id;

    public ?string $mailerlite_api_key;

    public ?string $newsletter_group_id;

    public static function group(): string
    {
        return 'site';
    }
}
