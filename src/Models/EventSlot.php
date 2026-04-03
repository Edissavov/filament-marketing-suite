<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $event_id
 * @property CarbonImmutable $date
 * @property string $start_time
 * @property string $end_time
 * @property int $capacity
 * @property bool $is_available
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Event $event
 * @property-read Collection<int, EventSubmission> $submissions
 *
 * @mixin \Eloquent
 */
class EventSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'is_available',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'capacity' => 'integer',
            'is_available' => 'boolean',
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
     * @return HasMany<EventSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(EventSubmission::class);
    }

    public function remainingCapacity(): int
    {
        return max(0, $this->capacity - $this->submissions()->count());
    }

    public function isAvailable(): bool
    {
        return $this->is_available && $this->remainingCapacity() > 0;
    }
}
