<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource;
use VasilGerginski\MarketingSuite\Models\ShortUrl;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListShortUrls extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All')),
            'active' => Tab::make(__('Active'))
                ->modifyQueryUsing(static fn (Builder $query) => $query->whereNull('deactivated_at'))
                ->badge(ShortUrl::query()->whereNull('deactivated_at')->count())
                ->badgeColor('success'),
            'deactivated' => Tab::make(__('Deactivated'))
                ->modifyQueryUsing(static fn (Builder $query) => $query->whereNotNull('deactivated_at'))
                ->badge(ShortUrl::query()->whereNotNull('deactivated_at')->count())
                ->badgeColor('danger'),
            'with_campaigns' => Tab::make(__('With Campaigns'))
                ->modifyQueryUsing(static fn (Builder $query) => $query->whereNotNull('utm_campaign'))
                ->badge(ShortUrl::query()->whereNotNull('utm_campaign')->count())
                ->badgeColor('info'),
            'recent' => Tab::make(__('Recent'))
                ->modifyQueryUsing(static fn (Builder $query) => $query->where('created_at', '>=', now()->subDays(7)))
                ->badge(ShortUrl::query()->where('created_at', '>=', now()->subDays(7))->count())
                ->badgeColor('gray'),
        ];
    }
}
