<?php

namespace VasilGerginski\MarketingSuite;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Color;
use Filament\Support\Facades\FilamentAsset;

class MarketingSuite implements Plugin
{
    public function getId(): string
    {
        return 'marketing-suite';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Theme::make('marketing-suite', __DIR__ . '/../resources/dist/marketing-suite.css'),
        ]);

        $panel
            ->font('DM Sans')
            ->primaryColor(Color::Amber)
            ->secondaryColor(Color::Gray)
            ->warningColor(Color::Amber)
            ->dangerColor(Color::Rose)
            ->successColor(Color::Green)
            ->grayColor(Color::Gray)
            ->theme('marketing-suite');
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
