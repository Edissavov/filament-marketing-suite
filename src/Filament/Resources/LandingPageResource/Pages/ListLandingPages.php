<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource;
use VasilGerginski\MarketingSuite\Models\Event;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Services\LandingPageAiGenerator;

class ListLandingPages extends ListRecords
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('aiGenerate')
                ->label(__('AI Generate'))
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->form([
                    TextInput::make('title')
                        ->label(__('Page Title'))
                        ->required()
                        ->maxLength(255),
                    Textarea::make('prompt')
                        ->label(__('Describe your landing page'))
                        ->placeholder(__('e.g., A landing page for a webinar about P2P investing for beginners, with registration form, FAQ, and testimonials'))
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data): void {
                    try {
                        $sections = app(LandingPageAiGenerator::class)->generate($data['prompt']);

                        // Auto-create an event for tracking
                        $event = Event::create([
                            'name' => $data['title'],
                            'event_type' => 'lead_generation',
                            'is_active' => true,
                        ]);

                        $page = LandingPage::create([
                            'title' => $data['title'],
                            'slug' => Str::slug($data['title']),
                            'goal_type' => 'custom',
                            'sections' => $sections,
                            'event_id' => $event->id,
                            'is_active' => false,
                        ]);

                        Notification::make()
                            ->title(__('Landing page generated'))
                            ->body(__('The AI has created :count sections for your landing page.', ['count' => count($sections)]))
                            ->success()
                            ->send();

                        $this->redirect(LandingPageResource::getUrl('edit', ['record' => $page]));
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title(__('Generation failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading(__('Generate Landing Page with AI'))
                ->modalSubmitActionLabel(__('Generate'))
                ->modalWidth('lg'),
            CreateAction::make(),
        ];
    }
}
