<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Components;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs as BaseTranslatableTabs;

/**
 * TranslatableTabs with safe defaults.
 *
 * The base component requires both locales() and localesLabels() to be set —
 * rendering throws "typed property must not be accessed before initialization"
 * otherwise. This subclass defaults the locales to the marketing-suite config
 * and derives the tab labels from the locale codes.
 */
class TranslatableTabs extends BaseTranslatableTabs
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->locales(static fn (): array => config('marketing-suite.locales', ['bg', 'en']));

        $this->localesLabels(function (): array {
            $labels = [];

            foreach ($this->evaluate($this->locales) as $key => $value) {
                $code = is_int($key) ? $value : $key;
                $labels[$code] = static::defaultLabel($code);
            }

            return $labels;
        });
    }

    protected static function defaultLabel(string $locale): string
    {
        if (function_exists('locale_get_display_language')) {
            return str(locale_get_display_language($locale, $locale))->ucfirst()->toString();
        }

        return mb_strtoupper($locale);
    }
}
