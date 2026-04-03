<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property int|null $landing_page_id
 * @property int|null $short_url_visit_id
 * @property int|null $event_slot_id
 * @property string $section_type
 * @property array<string, mixed>|null $data
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read Event $event
 * @property-read LandingPage|null $landingPage
 * @property-read ShortURLVisit|null $shortUrlVisit
 * @property-read EventSlot|null $eventSlot
 *
 * @mixin \Eloquent
 */
class EventSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'landing_page_id',
        'short_url_visit_id',
        'event_slot_id',
        'section_type',
        'data',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<LandingPage, $this>
     */
    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }

    /**
     * @return BelongsTo<ShortURLVisit, $this>
     */
    public function shortUrlVisit(): BelongsTo
    {
        return $this->belongsTo(ShortURLVisit::class);
    }

    /**
     * @return BelongsTo<EventSlot, $this>
     */
    public function eventSlot(): BelongsTo
    {
        return $this->belongsTo(EventSlot::class);
    }
}
