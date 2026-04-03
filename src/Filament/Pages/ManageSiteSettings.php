<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use VasilGerginski\MarketingSuite\Services\MailerLiteService;
use VasilGerginski\MarketingSuite\Settings\SiteSettings;

class ManageSiteSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = SiteSettings::class;

    public static function getNavigationLabel(): string
    {
        return __('Site Settings');
    }

    public function getHeading(): string | Htmlable
    {
        return __('Site Settings');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('General'))
                    ->schema([
                        TextInput::make('site_name')
                            ->label(__('Site Name'))
                            ->required(),
                        TextInput::make('tagline')
                            ->label(__('Tagline')),
                        TextInput::make('contact_email')
                            ->label(__('Contact Email'))
                            ->email(),
                        TextInput::make('contact_phone')
                            ->label(__('Contact Phone'))
                            ->tel(),
                    ])
                    ->columns(2),

                Section::make(__('Social Media'))
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url(),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url(),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url(),
                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url(),
                        TextInput::make('viber_url')
                            ->label('Viber')
                            ->url(),
                        TextInput::make('facebook_group_url')
                            ->label(__('Facebook Group'))
                            ->url(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make(__('Tracking & Analytics'))
                    ->schema([
                        TextInput::make('facebook_pixel_id')
                            ->label('Facebook Pixel ID')
                            ->placeholder('XXXXXXXXXXXXXXX'),
                        TextInput::make('mailerlite_api_key')
                            ->label('MailerLite API Key')
                            ->password()
                            ->revealable(),
                        Select::make('newsletter_group_id')
                            ->label(__('Newsletter Group'))
                            ->options(static function (): array {
                                $groups = app(MailerLiteService::class)->listGroups();

                                return collect($groups)->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->helperText(__('Subscribers from the homepage newsletter will be added to this group.')),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
