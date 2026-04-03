<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $help_category_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $sort_order
 * @property bool $is_published
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @mixin \Eloquent
 */
class HelpArticle extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['title', 'content'];

    protected $fillable = [
        'help_category_id',
        'title',
        'slug',
        'content',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<HelpCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');
    }

    /**
     * @return HasMany<HelpArticleFeedback, $this>
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(HelpArticleFeedback::class);
    }
}
