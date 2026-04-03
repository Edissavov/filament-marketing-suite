<?php

namespace VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\NewsletterSubscriberResource;

class EditNewsletterSubscriber extends EditRecord
{
    protected static string $resource = NewsletterSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
