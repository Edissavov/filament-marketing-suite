<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use VasilGerginski\MarketingSuite\MarketingSuitePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugin(MarketingSuitePlugin::make());
    }
}
