<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\TopBrowsersWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\TopOperatingSystemsWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\TrafficSourcesWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitDeviceBreakdownWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitStatsWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitTrendsWidget;
use VasilGerginski\FilamentShortUrl\Filament\Resources\ShortUrlResource\Pages\ViewShortUrl as BaseViewShortUrl;

class ViewShortUrl extends BaseViewShortUrl
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            VisitStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            VisitDeviceBreakdownWidget::class,
            TopOperatingSystemsWidget::class,
            TopBrowsersWidget::class,
            TrafficSourcesWidget::class,
            VisitTrendsWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): array|int
    {
        return 3;
    }
}
