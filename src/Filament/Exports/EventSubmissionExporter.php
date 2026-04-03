<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use VasilGerginski\MarketingSuite\Models\EventSubmission;

class EventSubmissionExporter extends Exporter
{
    protected static ?string $model = EventSubmission::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('data.name')
                ->label(__('Name')),
            ExportColumn::make('data.email')
                ->label(__('Email')),
            ExportColumn::make('data.phone')
                ->label(__('Phone')),
            ExportColumn::make('event.name')
                ->label(__('Event')),
            ExportColumn::make('landingPage.title')
                ->label(__('Landing Page')),
            ExportColumn::make('section_type')
                ->label(__('Type'))
                ->formatStateUsing(static fn (string $state): string => match ($state) {
                    'lead_form' => __('Lead Form'),
                    'event_registration' => __('Event Registration'),
                    'newsletter_signup' => __('Newsletter Signup'),
                    default => $state,
                }),
            ExportColumn::make('shortUrlVisit.shortURL.url_key')
                ->label(__('Short URL')),
            ExportColumn::make('shortUrlVisit.ip_address')
                ->label(__('IP Address')),
            ExportColumn::make('shortUrlVisit.referer_url')
                ->label(__('Referer')),
            ExportColumn::make('created_at')
                ->label(__('Submitted At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('Your leads export has completed and :count rows exported.', [
            'count' => Number::format($export->successful_rows),
        ]);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . __(
                ':count rows failed to export.',
                ['count' => Number::format($failedRowsCount)],
            );
        }

        return $body;
    }
}
