<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $help_article_id
 * @property string $ip_address
 * @property int|null $user_id
 * @property bool $is_helpful
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 *
 * @mixin \Eloquent
 */
class HelpArticleFeedback extends Model
{
    use HasFactory;

    protected $table = 'help_article_feedback';

    protected $fillable = [
        'help_article_id',
        'ip_address',
        'user_id',
        'is_helpful',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_helpful' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<HelpArticle, $this>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(HelpArticle::class, 'help_article_id');
    }
}
