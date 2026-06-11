<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use VasilGerginski\FilamentShortUrl\Models\ShortUrl as BaseShortUrl;

/**
 * Extended ShortURL model with conversion metrics.
 *
 * @property-read int $visits_count
 * @property-read int $conversion_count
 * @property-read float $conversion_rate
 *
 * @mixin \Eloquent
 */
class ShortUrl extends BaseShortUrl
{
    /** @var list<string> */
    protected $appends = [
        'visits_count',
        'conversion_count',
        'conversion_rate',
    ];

    /**
     * Build the public short URL for a key, honoring the configured prefix.
     */
    public static function defaultShortUrlFor(string $key): string
    {
        $base = rtrim(config('short-url.default_url') ?? config('app.url'), '/');
        $prefix = trim((string) config('short-url.prefix'), '/');

        return $base . ($prefix !== '' ? "/{$prefix}" : '') . "/{$key}";
    }

    /**
     * Get all submissions through visits.
     *
     * @return HasManyThrough<EventSubmission, ShortURLVisit, $this>
     */
    public function submissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            EventSubmission::class,
            ShortURLVisit::class,
            'short_url_id',
            'short_url_visit_id',
            'id',
            'id',
        );
    }

    public function getVisitsCountAttribute(): int
    {
        return (int) ($this->attributes['visits_count_cached'] ?? $this->visits()
            ->where(static function ($q): void {
                $q->whereNull('device_type')->orWhere('device_type', '!=', 'robot');
            })
            ->count());
    }

    public function getConversionCountAttribute(): int
    {
        if (isset($this->attributes['conversion_count_cached'])) {
            return (int) $this->attributes['conversion_count_cached'];
        }

        return EventSubmission::query()
            ->whereIn('short_url_visit_id', $this->visits()->select('id'))
            ->count();
    }

    public function getConversionRateAttribute(): float
    {
        $visits = $this->visits_count;

        if ($visits === 0) {
            return 0.0;
        }

        return round(($this->conversion_count / $visits) * 100, 2);
    }

    /**
     * Scope that adds aggregated visit/conversion stats via subqueries.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithMetrics(Builder $query): Builder
    {
        return $query
            ->withCount(['visits as visits_count_cached' => static function (Builder $q): void {
                $q->where(static function (Builder $q): void {
                    $q->whereNull('device_type')->orWhere('device_type', '!=', 'robot');
                });
            }])
            ->addSelect([
                'conversion_count_cached' => EventSubmission::query()
                    ->selectRaw('count(*)')
                    ->whereIn('event_submissions.short_url_visit_id', static function ($q): void {
                        $q
                            ->select('id')
                            ->from('short_url_visits')
                            ->whereColumn('short_url_visits.short_url_id', 'short_urls.id');
                    }),
            ]);
    }

    /**
     * Get the destination URL with UTM parameters appended.
     */
    public function getDestinationUrlWithUtm(): string
    {
        $params = array_filter([
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_term' => $this->utm_term,
            'utm_content' => $this->utm_content,
        ]);

        if (empty($params)) {
            return $this->destination_url;
        }

        $separator = str_contains($this->destination_url, '?') ? '&' : '?';

        return $this->destination_url . $separator . http_build_query($params);
    }
}
