<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $event_type
 * @property CarbonImmutable|null $event_date
 * @property string|null $event_time
 * @property string|null $event_end_time
 * @property string|null $location
 * @property int|null $max_capacity
 * @property bool $is_active
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, LandingPage> $landingPages
 * @property-read Collection<int, EventSlot> $slots
 * @property-read Collection<int, EventSubmission> $submissions
 *
 * @method static Builder<static>|Event active()
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'event_type',
        'event_date',
        'event_time',
        'event_end_time',
        'location',
        'max_capacity',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_active' => 'boolean',
            'max_capacity' => 'integer',
        ];
    }

    /**
     * @return HasMany<LandingPage, $this>
     */
    public function landingPages(): HasMany
    {
        return $this->hasMany(LandingPage::class);
    }

    /**
     * @return HasMany<EventSlot, $this>
     */
    public function slots(): HasMany
    {
        return $this->hasMany(EventSlot::class);
    }

    /**
     * @return HasMany<EventSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(EventSubmission::class);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isFullyBooked(): bool
    {
        if ($this->max_capacity === null) {
            return false;
        }

        return $this->submissions()->count() >= $this->max_capacity;
    }

    public function availableSlotsCount(): int
    {
        return $this->slots()
            ->where('is_available', true)
            ->get()
            ->filter(static fn (EventSlot $slot) => $slot->remainingCapacity() > 0)
            ->count();
    }
}
