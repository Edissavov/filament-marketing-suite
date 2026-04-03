<?php

namespace VasilGerginski\MarketingSuite;

use Filament\Contracts\Plugin;
use Filament\Panel;
use VasilGerginski\MarketingSuite\Filament\Pages\ManageSiteSettings;
use VasilGerginski\MarketingSuite\Filament\Resources\Authors\AuthorResource;
use VasilGerginski\MarketingSuite\Filament\Resources\BlogPostResource;
use VasilGerginski\MarketingSuite\Filament\Resources\Definitions\DefinitionResource;
use VasilGerginski\MarketingSuite\Filament\Resources\EventResource;
use VasilGerginski\MarketingSuite\Filament\Resources\EventSubmissions\EventSubmissionResource;
use VasilGerginski\MarketingSuite\Filament\Resources\FaqItemResource;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpArticleResource;
use VasilGerginski\MarketingSuite\Filament\Resources\HelpCategoryResource;
use VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource;
use VasilGerginski\MarketingSuite\Filament\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use VasilGerginski\MarketingSuite\Filament\Widgets\EventCalendarWidget;

class MarketingSuitePlugin implements Plugin
{
    protected bool $hasBlog = true;

    protected bool $hasLandingPages = true;

    protected bool $hasEvents = true;

    protected bool $hasFaq = true;

    protected bool $hasHelpCenter = true;

    protected bool $hasDefinitions = true;

    protected bool $hasNewsletter = true;

    protected bool $hasShortUrls = false;

    protected bool $hasSiteSettings = true;

    public function getId(): string
    {
        return 'marketing-suite';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources($this->getResources())
            ->pages($this->getPages())
            ->widgets($this->getWidgets());
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function blog(bool $condition = true): static
    {
        $this->hasBlog = $condition;

        return $this;
    }

    public function landingPages(bool $condition = true): static
    {
        $this->hasLandingPages = $condition;

        return $this;
    }

    public function events(bool $condition = true): static
    {
        $this->hasEvents = $condition;

        return $this;
    }

    public function faq(bool $condition = true): static
    {
        $this->hasFaq = $condition;

        return $this;
    }

    public function helpCenter(bool $condition = true): static
    {
        $this->hasHelpCenter = $condition;

        return $this;
    }

    public function definitions(bool $condition = true): static
    {
        $this->hasDefinitions = $condition;

        return $this;
    }

    public function newsletter(bool $condition = true): static
    {
        $this->hasNewsletter = $condition;

        return $this;
    }

    public function shortUrls(bool $condition = true): static
    {
        $this->hasShortUrls = $condition;

        return $this;
    }

    public function siteSettings(bool $condition = true): static
    {
        $this->hasSiteSettings = $condition;

        return $this;
    }

    public function hasBlog(): bool
    {
        return $this->hasBlog;
    }

    public function hasLandingPages(): bool
    {
        return $this->hasLandingPages;
    }

    public function hasEvents(): bool
    {
        return $this->hasEvents;
    }

    public function hasFaq(): bool
    {
        return $this->hasFaq;
    }

    public function hasHelpCenter(): bool
    {
        return $this->hasHelpCenter;
    }

    public function hasDefinitions(): bool
    {
        return $this->hasDefinitions;
    }

    public function hasNewsletter(): bool
    {
        return $this->hasNewsletter;
    }

    public function hasShortUrls(): bool
    {
        return $this->hasShortUrls;
    }

    public function hasSiteSettings(): bool
    {
        return $this->hasSiteSettings;
    }

    protected function getResources(): array
    {
        $resources = [];

        if ($this->hasBlog) {
            $resources[] = AuthorResource::class;
            $resources[] = BlogPostResource::class;
        }

        if ($this->hasLandingPages) {
            $resources[] = LandingPageResource::class;
        }

        if ($this->hasEvents) {
            $resources[] = EventResource::class;
            $resources[] = EventSubmissionResource::class;
        }

        if ($this->hasFaq) {
            $resources[] = FaqItemResource::class;
        }

        if ($this->hasHelpCenter) {
            $resources[] = HelpArticleResource::class;
            $resources[] = HelpCategoryResource::class;
        }

        if ($this->hasDefinitions) {
            $resources[] = DefinitionResource::class;
        }

        if ($this->hasNewsletter) {
            $resources[] = NewsletterSubscriberResource::class;
        }

        if ($this->hasShortUrls && class_exists(\VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource::class)) {
            $resources[] = \VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource::class;
        }

        return $resources;
    }

    protected function getPages(): array
    {
        $pages = [];

        if ($this->hasSiteSettings) {
            $pages[] = ManageSiteSettings::class;
        }

        return $pages;
    }

    protected function getWidgets(): array
    {
        $widgets = [];

        if ($this->hasEvents) {
            $widgets[] = EventCalendarWidget::class;
        }

        return $widgets;
    }
}
