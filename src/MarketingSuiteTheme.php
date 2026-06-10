<?php

namespace VasilGerginski\MarketingSuite;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;

class MarketingSuiteTheme implements Plugin
{
    public function getId(): string
    {
        return 'marketing-suite-theme';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Theme::make('marketing-suite', __DIR__ . '/../resources/dist/marketing-suite.css'),
        ]);

        $panel
            ->font('DM Sans')
            ->colors([
                'primary' => Color::Amber,
                'secondary' => Color::Gray,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'success' => Color::Green,
                'gray' => Color::Gray,
            ])
            ->theme('marketing-suite');
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
