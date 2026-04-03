<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources;

use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Pages;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\RelationManagers\ShortUrlSubmissionsRelationManager;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\TopBrowsersWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\TopOperatingSystemsWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\TrafficSourcesWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitDeviceBreakdownWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitStatsWidget;
use VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets\VisitTrendsWidget;
use VasilGerginski\MarketingSuite\Models\ShortUrl;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use VasilGerginski\FilamentShortUrl\Filament\Resources\ShortUrlResource as BaseShortUrlResource;

class ShortUrlResource extends BaseShortUrlResource
{
    protected static ?string $model = ShortUrl::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('destination_url')
                            ->label(__('Destination url'))
                            ->required()
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label(__('Description'))
                            ->placeholder(__('Enter a short description to help identify this URL'))
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('price')
                            ->label(__('Campaign Cost'))
                            ->placeholder('0.00')
                            ->numeric()
                            ->step(0.01)
                            ->helperText(__('Enter the cost of this campaign/URL for ROI tracking'))
                            ->columnSpan(['lg' => 4, 'xs' => 6]),
                        Select::make('currency')
                            ->label(__('Currency'))
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'BGN' => 'BGN (лв)',
                                'JPY' => 'JPY (¥)',
                                'CAD' => 'CAD (C$)',
                                'AUD' => 'AUD (A$)',
                            ])
                            ->default('EUR')
                            ->columnSpan(['lg' => 2, 'xs' => 6]),

                        Section::make(__('UTM Parameters'))
                            ->description(__(
                                'Add UTM parameters to track campaign performance. These will be automatically appended to the destination URL.',
                            ))
                            ->schema([
                                TextInput::make('utm_source')
                                    ->label('UTM Source')
                                    ->placeholder('google, facebook, newsletter')
                                    ->columnSpan(1),
                                TextInput::make('utm_medium')
                                    ->label('UTM Medium')
                                    ->placeholder('cpc, social, email')
                                    ->columnSpan(1),
                                TextInput::make('utm_campaign')
                                    ->label('UTM Campaign')
                                    ->placeholder('spring_sale, awareness_campaign')
                                    ->columnSpan(2),
                                TextInput::make('utm_term')
                                    ->label('UTM Term')
                                    ->placeholder('keyword, audience_segment')
                                    ->columnSpan(1),
                                TextInput::make('utm_content')
                                    ->label('UTM Content')
                                    ->placeholder('ad_variant_a, banner_top')
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->columnSpanFull(),

                    ])
                    ->columns(6),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('destination_url')
                    ->label(__('Destination url'))
                    ->searchable()
                    ->limit(40)
                    ->tooltip(static fn ($state) => $state)
                    ->toggleable(),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(30)
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('price')
                    ->label(__('Cost'))
                    ->money(fn ($record) => $record->currency ?? 'EUR')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('utm_source')
                    ->label(__('Source'))
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('utm_medium')
                    ->label(__('Medium'))
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('utm_campaign')
                    ->label(__('Campaign'))
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('default_short_url')
                    ->label(__('Default short url'))
                    ->copyable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('visits_count')
                    ->label(__('Visits'))
                    ->numeric()
                    ->alignCenter()
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->color('gray')
                    ->sortable(query: static function ($query, $direction) {
                        return $query->orderBy('visits_count_cached', $direction);
                    })
                    ->toggleable(),

                TextColumn::make('conversion_count')
                    ->label(__('Conversions'))
                    ->numeric()
                    ->alignCenter()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->sortable(query: static function ($query, $direction) {
                        return $query->orderBy('conversion_count_cached', $direction);
                    })
                    ->toggleable(),

                TextColumn::make('conversion_rate')
                    ->label(__('Conversion Rate'))
                    ->suffix('%')
                    ->alignCenter()
                    ->color(static fn ($state) => $state > 5 ? 'success' : ($state > 2 ? 'warning' : 'gray'))
                    ->weight('bold')
                    ->toggleable(),
            ])
            ->filters([])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Marketing');
    }

    public static function getWidgets(): array
    {
        return [
            VisitStatsWidget::class,
            VisitDeviceBreakdownWidget::class,
            TopOperatingSystemsWidget::class,
            TopBrowsersWidget::class,
            TrafficSourcesWidget::class,
            VisitTrendsWidget::class,
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewShortUrl::class,
            Pages\EditShortUrl::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            ShortUrlSubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShortUrls::route('/'),
            'create' => Pages\CreateShortUrl::route('/create'),
            'view' => Pages\ViewShortUrl::route('/{record}'),
            'edit' => Pages\EditShortUrl::route('/{record}/edit'),
        ];
    }
}
