<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\EventResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventSubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = null;

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('Submissions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section_type')
                    ->label(__('Section Type'))
                    ->badge()
                    ->color(static fn (string $state): string => match ($state) {
                        'lead_form' => 'primary',
                        'event_registration' => 'warning',
                        'newsletter_signup' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(static fn (string $state): string => match ($state) {
                        'lead_form' => __('Lead Form'),
                        'event_registration' => __('Event Registration'),
                        'newsletter_signup' => __('Newsletter Signup'),
                        default => $state,
                    }),
                TextColumn::make('eventSlot.date')
                    ->label(__('Slot'))
                    ->formatStateUsing(static function ($state, $record): string {
                        if (! $record->eventSlot) {
                            return '-';
                        }

                        return $record->eventSlot->date->format('d M Y') . ' ' . $record->eventSlot->start_time;
                    })
                    ->placeholder('-'),
                TextColumn::make('data')
                    ->label(__('Data'))
                    ->formatStateUsing(static function ($state): string {
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                        }

                        if (empty($state) || ! is_array($state)) {
                            return '-';
                        }

                        return collect($state)
                            ->map(static fn ($value, $key) => "{$key}: {$value}")
                            ->take(3)
                            ->implode(', ');
                    })
                    ->limit(80),
                TextColumn::make('landingPage.title')
                    ->label(__('Landing Page'))
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label(__('Submitted'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
