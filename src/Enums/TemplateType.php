<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TemplateType: string implements HasLabel
{
    case Email = 'email';
    case Notification = 'notification';
    case Document = 'document';
    case Contract = 'contract';

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::Email => __('Email'),
            self::Notification => __('Notification'),
            self::Document => __('Document'),
            self::Contract => __('Contract'),
        };
    }
}
