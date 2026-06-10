<?php

use AshAllenDesign\ShortURL\Providers\ShortURLProvider;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSettings\LaravelSettingsServiceProvider;
use VasilGerginski\MarketingSuite\Filament\Exports\EventSubmissionExporter;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitTrendsWidget;
use VasilGerginski\MarketingSuite\MarketingSuitePlugin;
use VasilGerginski\MarketingSuite\MarketingSuiteTheme;

it('exposes every publish tag used by the install command', function () {
    $tags = [
        [LaravelSettingsServiceProvider::class, 'migrations'],
        [ShortURLProvider::class, 'short-url-migrations'],
        [ShortURLProvider::class, 'short-url-config'],
        [null, 'filament-short-url-migrations'],
        [null, 'marketing-suite-migrations'],
        [null, 'marketing-suite-config'],
    ];

    foreach ($tags as [$provider, $tag]) {
        expect(ServiceProvider::pathsToPublish($provider, $tag))
            ->not->toBeEmpty("Publish tag [{$tag}] resolved to nothing — the install command would silently skip it.");
    }
});

it('registers all panel resources, pages and widgets, and their classes load', function () {
    $panel = Panel::make()->id('integrity-test');

    MarketingSuitePlugin::make()->register($panel);

    $registered = [
        ...$panel->getResources(),
        ...$panel->getPages(),
        ...$panel->getWidgets(),
        EventSubmissionExporter::class,
        VisitTrendsWidget::class,
        MarketingSuiteTheme::class,
    ];

    expect($panel->getResources())->not->toBeEmpty();

    foreach ($registered as $class) {
        // class_exists() forces the autoloader to actually load the class,
        // which surfaces fatal signature mismatches against Filament parents.
        expect(class_exists($class))->toBeTrue("Class [{$class}] failed to load.");
    }
});
