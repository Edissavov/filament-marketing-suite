<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Enums;

use Filament\Support\Contracts\HasLabel;

enum ContentType: string implements HasLabel
{
    case Html = 'html';
    case Text = 'text';
    case Markdown = 'markdown';

    public function getLabel(): string
    {
        return match ($this) {
            self::Html => __('HTML'),
            self::Text => __('Text'),
            self::Markdown => __('Markdown'),
        };
    }
}
