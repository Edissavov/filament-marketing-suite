<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\EventSubmissions;

use VasilGerginski\MarketingSuite\Filament\Exports\EventSubmissionExporter;
use VasilGerginski\MarketingSuite\Filament\Resources\EventSubmissions\Pages\ManageEventSubmissions;
use VasilGerginski\MarketingSuite\Models\EventSubmission;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EventSubmissionResource extends Resource
{
    protected static ?string $model = EventSubmission::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function getModelLabel(): string
    {
        return __('Lead');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Leads');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Marketing');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Submission Context'))
                    ->schema([
                        TextEntry::make('event.name')
                            ->label(__('Event')),
                        TextEntry::make('landingPage.title')
                            ->label(__('Landing Page'))
                            ->placeholder('-'),
                        TextEntry::make('section_type')
                            ->label(__('Form Type'))
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
                        TextEntry::make('created_at')
                            ->label(__('Submitted At'))
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make(__('Submitted Data'))
                    ->description(__('Dynamically extracted from the form submission'))
                    ->schema(static function (EventSubmission $record): array {
                        $data = $record->data ?? [];

                        return collect($data)
                            ->map(static function ($value, $key) {
                                $label = match ($key) {
                                    'name' => __('Name'),
                                    'email' => __('Email'),
                                    'phone' => __('Phone'),
                                    'selected_slot' => __('Selected Time Slot'),
                                    default => Str::headline((string) $key),
                                };

                                return TextEntry::make("data.{$key}")
                                    ->label($label)
                                    ->weight('semibold')
                                    ->placeholder('-');
                            })
                            ->toArray();
                    })
                    ->columns(2),

                Section::make(__('Marketing & Tracking'))
                    ->schema([
                        TextEntry::make('shortUrlVisit.shortURL.url_key')
                            ->label(__('Short URL Key'))
                            ->placeholder('-')
                            ->prefix('/')
                            ->color('primary')
                            ->weight('bold'),
                        TextEntry::make('shortUrlVisit.ip_address')
                            ->label(__('IP Address'))
                            ->placeholder('-'),
                        TextEntry::make('shortUrlVisit.browser')
                            ->label(__('Browser'))
                            ->placeholder('-')
                            ->formatStateUsing(static fn ($state, $record) => $state
                                ? "{$state} ({$record->shortUrlVisit?->operating_system})"
                                : '-'),
                        TextEntry::make('shortUrlVisit.referer_url')
                            ->label(__('Referer'))
                            ->placeholder('-')
                            ->limit(50),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data.name')
                    ->label(__('Name'))
                    ->searchable(query: static function ($query, $search) {
                        return $query->where('data->name', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('data.email')
                    ->label(__('Email'))
                    ->searchable(query: static function ($query, $search) {
                        return $query->where('data->email', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('data.phone')
                    ->label(__('Phone'))
                    ->searchable(query: static function ($query, $search) {
                        return $query->where('data->phone', 'like', "%{$search}%");
                    })
                    ->placeholder('-'),
                TextColumn::make('landingPage.title')
                    ->label(__('Landing Page'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('event.name')
                    ->label(__('Event'))
                    ->searchable()
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
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('landing_page_id')
                    ->label(__('Landing Page'))
                    ->relationship('landingPage', 'title'),
                SelectFilter::make('event_id')
                    ->label(__('Event'))
                    ->relationship('event', 'name'),
                SelectFilter::make('section_type')
                    ->label(__('Type'))
                    ->options([
                        'lead_form' => __('Lead Form'),
                        'event_registration' => __('Event Registration'),
                        'newsletter_signup' => __('Newsletter Signup'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(EventSubmissionExporter::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEventSubmissions::route('/'),
        ];
    }
}
