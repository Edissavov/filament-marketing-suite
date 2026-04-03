<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShortUrlSubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = null;

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('Leads');
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal Information'))
                    ->schema([
                        TextEntry::make('data.name')
                            ->label(__('Name'))
                            ->placeholder('-'),
                        TextEntry::make('data.email')
                            ->label(__('Email'))
                            ->placeholder('-'),
                        TextEntry::make('data.phone')
                            ->label(__('Phone'))
                            ->placeholder('-'),
                        TextEntry::make('data.selected_slot')
                            ->label(__('Time Slot'))
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make(__('Full Form Data'))
                    ->schema([
                        TextEntry::make('data')
                            ->label('')
                            ->formatStateUsing(static function ($state): string {
                                if (is_string($state)) {
                                    $state = json_decode($state, true);
                                }

                                if (empty($state) || ! is_array($state)) {
                                    return '-';
                                }

                                return collect($state)
                                    ->map(static fn ($value, $key) => "**{$key}**: {$value}")
                                    ->implode("\n\n");
                            })
                            ->markdown(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('data.name')
                    ->label(__('Name'))
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('data.email')
                    ->label(__('Email'))
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('data.phone')
                    ->label(__('Phone'))
                    ->placeholder('-'),
                TextColumn::make('event.name')
                    ->label(__('Event'))
                    ->sortable(),
                TextColumn::make('section_type')
                    ->label(__('Type'))
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
                TextColumn::make('created_at')
                    ->label(__('Submitted'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
