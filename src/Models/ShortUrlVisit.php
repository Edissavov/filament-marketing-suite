<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use AshAllenDesign\ShortURL\Models\ShortURLVisit as BaseShortURLVisit;

/**
 * Extended ShortURLVisit model with conversion tracking.
 *
 * @property int $id
 * @property int $short_url_id
 * @property string|null $visitor_token
 * @property string|null $session_id
 * @property \Carbon\CarbonImmutable|null $reached_registration_at
 *
 * @mixin \Eloquent
 */
class ShortUrlVisit extends BaseShortURLVisit
{
    protected $table = 'short_url_visits';

    protected $fillable = [
        'short_url_id',
        'ip_address',
        'operating_system',
        'operating_system_version',
        'browser',
        'browser_version',
        'referer_url',
        'device_type',
        'visited_at',
        'visitor_token',
        'session_id',
        'reached_registration_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
            'reached_registration_at' => 'datetime',
        ];
    }

    /**
     * Generate a unique visitor token.
     */
    public static function generateVisitorToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
