<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\Definitions\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use VasilGerginski\MarketingSuite\Filament\Components\TranslatableTabs;

class DefinitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('translations')
                    ->locales(['bg', 'en'])
                    ->schema([
                        TextInput::make('term')
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
