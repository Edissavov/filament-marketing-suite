<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use VasilGerginski\MarketingSuite\Filament\Resources\EventResource\Pages;
use VasilGerginski\MarketingSuite\Filament\Resources\EventResource\RelationManagers\EventSubmissionsRelationManager;
use VasilGerginski\MarketingSuite\Models\Event;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar';

    public static function getModelLabel(): string
    {
        return __('Event');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Events');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Marketing');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Event Info'))
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Select::make('event_type')
                            ->label(__('Event Type'))
                            ->options([
                                'lead_generation' => __('Lead Generation'),
                                'event_registration' => __('Event Registration'),
                                'newsletter' => __('Newsletter'),
                                'consultation' => __('Consultation'),
                            ])
                            ->default('lead_generation')
                            ->required()
                            ->live(),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ]),

                Section::make(__('Schedule'))
                    ->schema([
                        DatePicker::make('event_date')
                            ->label(__('Event Date')),
                        TimePicker::make('event_time')
                            ->label(__('Start Time')),
                        TimePicker::make('event_end_time')
                            ->label(__('End Time')),
                        TextInput::make('location')
                            ->label(__('Location'))
                            ->placeholder(__('Online, Office address, etc.')),
                        TextInput::make('max_capacity')
                            ->label(__('Max Capacity'))
                            ->numeric()
                            ->minValue(1)
                            ->helperText(__('Leave empty for unlimited')),
                    ])
                    ->collapsible(),

                Section::make(__('Consultation Slots'))
                    ->description(__('Manage available booking slots for consultations.'))
                    ->schema([
                        Repeater::make('slots')
                            ->relationship()
                            ->label('')
                            ->schema([
                                DatePicker::make('date')
                                    ->label(__('Date'))
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->label(__('Start'))
                                    ->required(),
                                TimePicker::make('end_time')
                                    ->label(__('End'))
                                    ->required(),
                                TextInput::make('capacity')
                                    ->label(__('Capacity'))
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                                Toggle::make('is_available')
                                    ->label(__('Available'))
                                    ->default(true),
                            ])
                            ->columns(5)
                            ->reorderable(false)
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->itemLabel(static fn (array $state): ?string => isset($state['date'], $state['start_time'])
                                ? $state['date'] . ' ' . $state['start_time'] . ' - ' . ($state['end_time'] ?? '')
                                : null),
                    ])
                    ->visible(static fn (callable $get): bool => $get('event_type') === 'consultation')
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('event_type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(static fn (string $state): string => match ($state) {
                        'lead_generation' => 'primary',
                        'event_registration' => 'warning',
                        'newsletter' => 'success',
                        'consultation' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(static fn (string $state): string => match ($state) {
                        'lead_generation' => __('Lead Generation'),
                        'event_registration' => __('Event Registration'),
                        'newsletter' => __('Newsletter'),
                        'consultation' => __('Consultation'),
                        default => $state,
                    }),
                TextColumn::make('event_date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('event_time')
                    ->label(__('Time'))
                    ->placeholder('-'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('Active')),
                TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label(__('Submissions'))
                    ->badge()
                    ->color('success'),
                TextColumn::make('landing_pages_count')
                    ->counts('landingPages')
                    ->label(__('Landing Pages'))
                    ->badge()
                    ->color('primary'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('Active')),
                SelectFilter::make('event_type')
                    ->label(__('Type'))
                    ->options([
                        'lead_generation' => __('Lead Generation'),
                        'event_registration' => __('Event Registration'),
                        'newsletter' => __('Newsletter'),
                        'consultation' => __('Consultation'),
                    ]),
            ])
            ->actions([
                Action::make('duplicate')
                    ->label(__('Duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(static function (Event $record): void {
                        $newEvent = $record->replicate();
                        $newEvent->name = $record->name . ' (' . __('Copy') . ')';
                        $newEvent->is_active = false;
                        $newEvent->save();

                        Notification::make()
                            ->title(__('Event duplicated'))
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('activate')
                        ->label(__('Activate'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(static fn (Collection $records) => $records->each->update(['is_active' => true])),
                    BulkAction::make('deactivate')
                        ->label(__('Deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(static fn (Collection $records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            EventSubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
