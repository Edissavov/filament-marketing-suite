<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\EventResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventSlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';

    protected static ?string $title = null;

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('Time Slots');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required(),
                TimePicker::make('start_time')
                    ->label(__('Start Time'))
                    ->required(),
                TimePicker::make('end_time')
                    ->label(__('End Time'))
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label(__('Start'))
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label(__('End')),
                TextColumn::make('capacity')
                    ->label(__('Capacity'))
                    ->badge(),
                TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label(__('Booked'))
                    ->badge()
                    ->color('warning'),
                IconColumn::make('is_available')
                    ->label(__('Available'))
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('date');
    }
}
