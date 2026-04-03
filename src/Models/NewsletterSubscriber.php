<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property CarbonImmutable $subscribed_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @mixin \Eloquent
 */
class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'subscribed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
        ];
    }
}
