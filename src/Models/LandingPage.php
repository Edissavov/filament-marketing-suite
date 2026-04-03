<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $meta_description
 * @property array<int, array{type: string, data: array<string, mixed>}>|null $sections
 * @property string $goal_type
 * @property string $template
 * @property bool $is_active
 * @property bool $enable_analytics
 * @property string|null $tracking_code
 * @property string|null $utm_source
 * @property string|null $utm_medium
 * @property string|null $utm_campaign
 * @property string|null $og_image
 * @property int|null $event_id
 * @property string|null $mailerlite_group_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read string|null $tracking_url
 * @property-read Event|null $event
 *
 * @mixin \Eloquent
 */
class LandingPage extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['title', 'meta_description', 'sections'];

    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'sections',
        'goal_type',
        'theme',
        'template',
        'is_active',
        'enable_analytics',
        'tracking_code',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'og_image',
        'event_id',
        'mailerlite_group_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'is_active' => 'boolean',
            'enable_analytics' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getTrackingUrlAttribute(): ?string
    {
        $params = array_filter([
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
        ]);

        if (empty($params)) {
            return null;
        }

        return route('landing-page', $this->slug).'?'.http_build_query($params);
    }
}
