<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Pages;

use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterSubscribers extends ListRecords
{
    protected static string $resource = NewsletterSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
