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
 * @property array<array-key, array{id?: string, type?: string, data?: array<string, mixed>}>|null $sections
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
 * @property-read string $url
 * @property-read string|null $tracking_url
 * @property-read Event|null $event
 *
 * @mixin \Eloquent
 */
class LandingPage extends Model
{
    use HasFactory;
    use HasTranslations;

    // Note: sections is intentionally NOT translatable. The form edits it
    // with a single (non-localized) Builder, and Filament fills edit forms
    // from attributesToArray(), where translatable attributes return the
    // full locale map — which the Builder cannot render.
    public array $translatable = ['title', 'meta_description'];

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

    protected static function booted(): void
    {
        // Every landing page gets its own tracking event so form submissions
        // are always captured as leads, regardless of how the page was created.
        static::created(static function (self $page): void {
            if ($page->event_id) {
                return;
            }

            $event = Event::create([
                'name' => $page->title,
                'event_type' => match ($page->goal_type) {
                    'event' => 'event_registration',
                    'newsletter' => 'newsletter',
                    default => 'lead_generation',
                },
                'is_active' => true,
            ]);

            $page->updateQuietly(['event_id' => $event->id]);
        });
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

    /**
     * Get the public URL of the landing page.
     */
    public function getUrlAttribute(): string
    {
        return route('marketing-suite.landing', $this->slug);
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

        return $this->url . '?' . http_build_query($params);
    }
}
