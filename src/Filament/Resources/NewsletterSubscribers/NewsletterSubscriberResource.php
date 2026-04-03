<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Pages\CreateNewsletterSubscriber;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Pages\EditNewsletterSubscriber;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Schemas\NewsletterSubscriberForm;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\Tables\NewsletterSubscribersTable;
use VasilGerginski\MarketingSuite\Models\NewsletterSubscriber;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function form(Schema $schema): Schema
    {
        return NewsletterSubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterSubscribersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterSubscribers::route('/'),
            'create' => CreateNewsletterSubscriber::route('/create'),
            'edit' => EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}
