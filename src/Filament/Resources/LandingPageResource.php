<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources;

use VasilGerginski\MarketingSuite\Filament\Components\TranslatableTabs;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use VasilGerginski\MarketingSuite\Filament\Resources\Concerns\HasSectionBlocks;
use VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource\Pages;
use VasilGerginski\MarketingSuite\Models\Event;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Services\MailerLiteService;

class LandingPageResource extends Resource
{
    use HasSectionBlocks;

    protected static ?string $model = LandingPage::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';

    public static function getModelLabel(): string
    {
        return __('Landing Page');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Landing Pages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Marketing');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('translations')
                    ->locales(['bg', 'en'])
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(static function (string $operation, string $state, Set $set): void {
                                if ($operation !== 'create') {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('meta_description')
                            ->label(__('SEO Description'))
                            ->maxLength(500),
                    ])
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('theme')
                    ->label(__('Color Theme'))
                    ->options([
                        'blue' => __('Blue (Default)'),
                        'green' => __('Green'),
                        'purple' => __('Purple'),
                        'orange' => __('Orange'),
                        'dark' => __('Dark'),
                        'red' => __('Red'),
                    ])
                    ->default('blue')
                    ->required(),
                Select::make('goal_type')
                    ->options([
                        'lead_generation' => __('Lead Generation'),
                        'investor_education' => __('Investor Education'),
                        'product_launch' => __('Product Launch'),
                        'event' => __('Event'),
                        'newsletter' => __('Newsletter'),
                        'custom' => __('Custom'),
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(static function (string $state, Set $set, callable $get): void {
                        $template = self::getTemplateForGoalType($state);

                        if (! empty($template)) {
                            $currentSections = $get('sections') ?? [];
                            if (empty($currentSections)) {
                                $templateWithIds = collect($template)->mapWithKeys(static function ($section) {
                                    $id = bin2hex(random_bytes(16));
                                    $section['id'] = $id;

                                    return [$id => $section];
                                })->toArray();

                                $set('sections', $templateWithIds);
                            }
                        }

                        $eventTypeMap = [
                            'lead_generation' => 'lead_generation',
                            'investor_education' => 'lead_generation',
                            'product_launch' => 'lead_generation',
                            'event' => 'event_registration',
                            'newsletter' => 'newsletter',
                            'custom' => 'lead_generation',
                        ];

                        $eventType = $eventTypeMap[$state] ?? null;

                        if ($eventType) {
                            $matchingEvent = Event::query()
                                ->where('is_active', true)
                                ->where('event_type', $eventType)
                                ->first();

                            if ($matchingEvent) {
                                $set('event_id', (string) $matchingEvent->id);
                            }
                        }
                    }),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(false)
                    ->inline(false),

                Section::make(__('Sections'))
                    ->description(__('Drag to reorder. Click to expand and edit each section.'))
                    ->schema([
                        Builder::make('sections')
                            ->label('')
                            ->blocks(static::sectionBlocks())
                            ->blockPreviews()
                            ->reorderable()
                            ->cloneable()
                            ->blockIcons()
                            ->blockNumbers(false)
                            ->blockPickerColumns(3)
                            ->blockPickerWidth('2xl')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make(__('Event Tracking'))
                    ->schema([
                        Select::make('event_id')
                            ->label(__('Track Event'))
                            ->options(static fn () => Event::query()->where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(static function (?string $state, Set $set, callable $get): void {
                                if (! $state) {
                                    return;
                                }

                                $event = Event::find($state);

                                if (! $event) {
                                    return;
                                }

                                $sectionType = match ($event->event_type) {
                                    'lead_generation' => 'lead_form',
                                    'event_registration', 'consultation' => 'event_registration',
                                    'newsletter' => 'newsletter_signup',
                                    default => null,
                                };

                                if (! $sectionType) {
                                    return;
                                }

                                $currentSections = $get('sections') ?? [];

                                $sectionData = self::getDefaultSectionData($sectionType, $event);
                                $id = bin2hex(random_bytes(16));

                                $currentSections[$id] = [
                                    'id' => $id,
                                    'type' => $sectionType,
                                    'data' => $sectionData,
                                ];
                                $set('sections', $currentSections);
                            })
                            ->helperText(__('Selecting an event auto-adds the matching form section below.')),
                        Select::make('mailerlite_group_id')
                            ->label(__('MailerLite Group'))
                            ->options(static function (): array {
                                $groups = app(MailerLiteService::class)->listGroups();

                                return collect($groups)->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label(__('Group Name'))
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(static function (array $data): ?string {
                                return app(MailerLiteService::class)->createGroup($data['name']);
                            })
                            ->helperText(__(
                                'Subscribers from this landing page will be added to this MailerLite group.',
                            )),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->color('primary'),
                TextColumn::make('goal_type')
                    ->badge()
                    ->color(static fn (string $state): string => match ($state) {
                        'lead_generation' => 'primary',
                        'investor_education' => 'info',
                        'product_launch' => 'success',
                        'event' => 'warning',
                        'newsletter' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(static fn (string $state): string => match ($state) {
                        'lead_generation' => __('Lead Generation'),
                        'investor_education' => __('Investor Education'),
                        'product_launch' => __('Product Launch'),
                        'event' => __('Event'),
                        'newsletter' => __('Newsletter'),
                        'custom' => __('Custom'),
                        default => $state,
                    }),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('Active')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('goal_type')
                    ->options([
                        'lead_generation' => __('Lead Generation'),
                        'investor_education' => __('Investor Education'),
                        'product_launch' => __('Product Launch'),
                        'event' => __('Event'),
                        'newsletter' => __('Newsletter'),
                        'custom' => __('Custom'),
                    ]),
                TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label(__('Preview'))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(
                        static fn (LandingPage $record): string => route('marketing-suite.landing', $record->slug) . '?preview=true',
                    )
                    ->openUrlInNewTab(),
                Action::make('duplicate')
                    ->label(__('Duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(static function (LandingPage $record): void {
                        $newPage = $record->replicate();
                        $newPage->title = $record->title . ' (Copy)';
                        $newPage->slug = $record->slug . '-' . Str::random(5);
                        $newPage->is_active = false;
                        $newPage->save();

                        Notification::make()
                            ->title(__('Landing page duplicated'))
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPages::route('/'),
            'create' => Pages\CreateLandingPage::route('/create'),
            'edit' => Pages\EditLandingPage::route('/{record}/edit'),
        ];
    }

    // ── Default Section Data ────────────────────────────────────

    /**
     * @return array<string, mixed>
     */
    private static function getDefaultSectionData(string $sectionType, Event $event): array
    {
        return match ($sectionType) {
            'lead_form' => [
                'title' => $event->name,
                'subtitle' => $event->description ?? __('Fill in your details and we will get back to you.'),
                'buttonText' => __('Submit'),
                'successMessage' => __('Thank you! We will get back to you soon.'),
                'fields' => [
                    ['name' => 'name', 'label' => __('Name'), 'type' => 'text', 'required' => true],
                    ['name' => 'email', 'label' => __('Email'), 'type' => 'email', 'required' => true],
                    ['name' => 'phone', 'label' => __('Phone'), 'type' => 'phone', 'required' => true],
                ],
            ],
            'event_registration' => [
                'title' => $event->name,
                'subtitle' => $event->description ?? __('Register for this event.'),
                'eventDate' => $event->event_date?->toDateString(),
                'eventTime' => $event->event_time,
                'eventLocation' => $event->location,
                'buttonText' => __('Register'),
                'successMessage' => __('You have been registered successfully!'),
                'fields' => [
                    ['name' => 'name', 'label' => __('Name'), 'type' => 'text', 'required' => true],
                    ['name' => 'email', 'label' => __('Email'), 'type' => 'email', 'required' => true],
                    ['name' => 'phone', 'label' => __('Phone'), 'type' => 'phone', 'required' => true],
                ],
                'slots' => [],
            ],
            'newsletter_signup' => [
                'title' => $event->name,
                'subtitle' => $event->description ?? __('Subscribe to our newsletter.'),
                'buttonText' => __('Subscribe'),
                'successMessage' => __('You have been subscribed!'),
                'privacyText' => __('We respect your privacy.'),
            ],
            default => [],
        };
    }

    // ── Templates ───────────────────────────────────────────────

    /**
     * @return array<int, array{type: string, data: array<string, mixed>}>
     */
    private static function getTemplateForGoalType(string $goalType): array
    {
        return match ($goalType) {
            'lead_generation' => [
                [
                    'type' => 'hero_section',
                    'data' => [
                        'badge' => 'Доходност до 12% годишно',
                        'title' => 'Инвестирайте в кредити с обезпечение',
                        'subtitle' => 'Диверсифицирайте портфолиото си с P2P инвестиции в обезпечени кредити. Минимална инвестиция от 10 EUR.',
                        'buttons' => [
                            ['text' => __('Get Started'), 'link' => '#lead-form', 'style' => 'primary'],
                            ['text' => 'Научете повече', 'link' => '#solution', 'style' => 'outline'],
                        ],
                        'statistics' => [
                            ['value' => '12%', 'description' => 'Средна годишна доходност'],
                            ['value' => '10 EUR', 'description' => 'Минимална инвестиция'],
                            ['value' => '5000+', 'description' => 'Активни инвеститори'],
                        ],
                    ],
                ],
                [
                    'type' => 'lead_form',
                    'data' => [
                        'title' => 'Получете безплатна консултация',
                        'subtitle' => 'Оставете данните си и наш консултант ще се свърже с вас.',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Име', 'type' => 'text', 'required' => true],
                            ['name' => 'email', 'label' => 'Имейл', 'type' => 'email', 'required' => true],
                            ['name' => 'phone', 'label' => 'Телефон', 'type' => 'phone', 'required' => true],
                        ],
                        'buttonText' => __('Submit'),
                        'successMessage' => 'Благодарим! Ще се свържем с вас до 24 часа.',
                    ],
                ],
                [
                    'type' => 'cta_section',
                    'data' => [
                        'title' => 'Готови ли сте да инвестирате?',
                        'subtitle' => 'Присъединете се към хилядите инвеститори, които вече печелят с Find2Be.',
                        'buttonText' => __('Start Now'),
                        'buttonLink' => 'https://invest.find2be.com/user-registration',
                        'features' => [
                            ['text' => 'Безплатна регистрация'],
                            ['text' => 'Без скрити такси'],
                            ['text' => 'Поддръжка на български'],
                        ],
                    ],
                ],
            ],
            'event' => [
                [
                    'type' => 'hero_section',
                    'data' => [
                        'badge' => 'Безплатно събитие',
                        'title' => 'Уебинар: Как да започнете с P2P инвестиране',
                        'subtitle' => 'Присъединете се към нашия безплатен онлайн уебинар и научете основите на P2P инвестирането от експертите на Find2Be.',
                        'buttons' => [['text' => __('Register Now'), 'link' => '#register', 'style' => 'primary']],
                        'statistics' => [
                            ['value' => '60 мин', 'description' => 'Продължителност'],
                            ['value' => 'Онлайн', 'description' => 'Zoom среща'],
                            ['value' => 'Безплатно', 'description' => 'Без такса участие'],
                        ],
                    ],
                ],
                [
                    'type' => 'event_registration',
                    'data' => [
                        'title' => 'Регистрирайте се за уебинара',
                        'subtitle' => 'Местата са ограничени. Запазете вашето сега.',
                        'buttonText' => __('Register'),
                        'successMessage' => 'Регистрирахте се успешно! Ще получите имейл с линк за присъединяване.',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Име', 'type' => 'text', 'required' => true],
                            ['name' => 'email', 'label' => 'Имейл', 'type' => 'email', 'required' => true],
                            ['name' => 'phone', 'label' => 'Телефон', 'type' => 'phone', 'required' => false],
                        ],
                    ],
                ],
            ],
            'newsletter' => [
                [
                    'type' => 'hero_section',
                    'data' => [
                        'badge' => 'Седмичен бюлетин',
                        'title' => 'Инвестиционни прозрения всяка седмица',
                        'subtitle' => 'Получавайте анализи на пазара, нови кредити и съвети за оптимизиране на портфолиото ви — директно в пощата ви.',
                        'buttons' => [['text' => __('Subscribe'), 'link' => '#newsletter', 'style' => 'primary']],
                    ],
                ],
                [
                    'type' => 'newsletter_signup',
                    'data' => [
                        'title' => 'Абонирайте се безплатно',
                        'subtitle' => 'Присъединете се към 3000+ инвеститори, които получават нашия бюлетин.',
                        'buttonText' => __('Subscribe'),
                        'successMessage' => 'Успешно се абонирахте! Проверете пощата си за потвърждение.',
                        'privacyText' => 'Ние уважаваме вашата поверителност. Можете да се отпишете по всяко време.',
                    ],
                ],
            ],
            default => [],
        };
    }
}
