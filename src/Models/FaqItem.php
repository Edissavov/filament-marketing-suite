<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property int $sort_order
 * @property bool $is_active
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @mixin \Eloquent
 */
class FaqItem extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['question', 'answer'];

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
