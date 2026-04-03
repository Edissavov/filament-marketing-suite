<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource;
use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Services\LandingPageAiGenerator;

class EditLandingPage extends EditRecord
{
    protected static string $resource = LandingPageResource::class;

    protected function getHeaderActions(): array
    {
        $shortUrl = ShortUrl::query()
            ->where('destination_url', $this->record->url)
            ->first();

        return [
            Action::make('aiRegenerate')
                ->label(__('AI Generate'))
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->form([
                    Textarea::make('prompt')
                        ->label(__('Describe your landing page'))
                        ->placeholder(__('e.g., A landing page for a webinar about P2P investing for beginners, with registration form, FAQ, and testimonials'))
                        ->required()
                        ->rows(4),
                ])
                ->requiresConfirmation()
                ->modalDescription(__('This will replace all existing sections with AI-generated content.'))
                ->action(function (array $data): void {
                    try {
                        $sections = app(LandingPageAiGenerator::class)->generate($data['prompt']);

                        $this->record->update(['sections' => $sections]);

                        Notification::make()
                            ->title(__('Sections regenerated'))
                            ->body(__('The AI has created :count sections.', ['count' => count($sections)]))
                            ->success()
                            ->send();

                        $this->fillForm();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title(__('Generation failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading(__('Regenerate with AI'))
                ->modalSubmitActionLabel(__('Generate'))
                ->modalWidth('lg'),
            Action::make('copyShortUrl')
                ->label($shortUrl ? __('Copy Short URL') : __('Create Short URL'))
                ->icon('heroicon-o-link')
                ->color('primary')
                ->action(function () use (&$shortUrl): void {
                    if (! $shortUrl) {
                        $shortUrl = ShortUrl::query()->create([
                            'destination_url' => $this->record->url,
                            'url_key' => 'lp-' . $this->record->slug,
                            'default_short_url' => url('/short/lp-' . $this->record->slug),
                            'description' => $this->record->title,
                            'track_visits' => true,
                            'track_ip_address' => true,
                            'track_operating_system' => true,
                            'track_operating_system_version' => true,
                            'track_browser' => true,
                            'track_browser_version' => true,
                            'track_referer_url' => true,
                            'track_device_type' => true,
                            'single_use' => false,
                            'is_active' => true,
                        ]);
                    }

                    Notification::make()
                        ->title(__('Short URL'))
                        ->body(url('/short/' . $shortUrl->url_key))
                        ->success()
                        ->send();
                }),
            Action::make('preview')
                ->label(__('Preview'))
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (): string => route('landing-page', $this->record->slug) . '?preview=true')
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
