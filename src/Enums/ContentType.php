<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ContentType: string implements HasLabel
{
    case Html = 'html';
    case Text = 'text';
    case Markdown = 'markdown';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Html => __('HTML'),
            self::Text => __('Text'),
            self::Markdown => __('Markdown'),
        };
    }
}
