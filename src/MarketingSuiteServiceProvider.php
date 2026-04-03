<?php

namespace VasilGerginski\MarketingSuite;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VasilGerginski\MarketingSuite\Commands\MarketingSuiteCommand;
use VasilGerginski\MarketingSuite\Testing\TestsMarketingSuite;

class MarketingSuiteServiceProvider extends PackageServiceProvider
{
    public static string $name = 'marketing-suite';

    public static string $viewNamespace = 'marketing-suite';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasTranslations()
            ->hasRoute('web')
            ->hasMigrations($this->getMigrations());
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName(),
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName(),
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/marketing-suite/{$file->getFilename()}"),
                ], 'marketing-suite-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsMarketingSuite);

        // Register Livewire Components
        $this->registerLivewireComponents();
    }

    protected function getAssetPackageName(): ?string
    {
        return 'vasilgerginski/marketing-suite';
    }

    protected function getAssets(): array
    {
        return [
            // Css::make('marketing-suite-styles', __DIR__ . '/../resources/dist/marketing-suite.css'),
        ];
    }

    protected function getCommands(): array
    {
        return [
            MarketingSuiteCommand::class,
        ];
    }

    protected function getIcons(): array
    {
        return [];
    }

    protected function getRoutes(): array
    {
        return [];
    }

    protected function getScriptData(): array
    {
        return [];
    }

    protected function getMigrations(): array
    {
        return [
            'create_authors_table',
            'create_blog_posts_table',
            'create_definitions_table',
            'create_faq_items_table',
            'create_help_categories_table',
            'create_help_articles_table',
            'create_help_article_feedback_table',
            'create_newsletter_subscribers_table',
            'create_events_table',
            'create_event_slots_table',
            'create_landing_pages_table',
            'create_event_submissions_table',
            'create_marketing_suite_settings',
        ];
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('marketing-suite::blog.index', \VasilGerginski\MarketingSuite\Livewire\Blog\Index::class);
        Livewire::component('marketing-suite::blog.show', \VasilGerginski\MarketingSuite\Livewire\Blog\Show::class);
        Livewire::component('marketing-suite::landing-page', \VasilGerginski\MarketingSuite\Livewire\LandingPage::class);
        Livewire::component('marketing-suite::newsletter-subscribe', \VasilGerginski\MarketingSuite\Livewire\NewsletterSubscribe::class);
        Livewire::component('marketing-suite::help-center', \VasilGerginski\MarketingSuite\Livewire\HelpCenter::class);
        Livewire::component('marketing-suite::help-center.search', \VasilGerginski\MarketingSuite\Livewire\HelpCenter\Search::class);
        Livewire::component('marketing-suite::contact-form', \VasilGerginski\MarketingSuite\Livewire\ContactForm::class);
        Livewire::component('marketing-suite::pages.blog', \VasilGerginski\MarketingSuite\Livewire\Pages\Blog::class);
        Livewire::component('marketing-suite::pages.our-authors', \VasilGerginski\MarketingSuite\Livewire\Pages\OurAuthors::class);
        Livewire::component('marketing-suite::pages.definicii', \VasilGerginski\MarketingSuite\Livewire\Pages\Definicii::class);

        // Landing Page Section Components
        Livewire::component('marketing-suite::landing-page-components.hero-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\HeroSection::class);
        Livewire::component('marketing-suite::landing-page-components.challenges-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\ChallengesSection::class);
        Livewire::component('marketing-suite::landing-page-components.solution-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\SolutionSection::class);
        Livewire::component('marketing-suite::landing-page-components.product-showcase', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\ProductShowcase::class);
        Livewire::component('marketing-suite::landing-page-components.testimonials-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\TestimonialsSection::class);
        Livewire::component('marketing-suite::landing-page-components.faq-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\FaqSection::class);
        Livewire::component('marketing-suite::landing-page-components.cta-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\CtaSection::class);
        Livewire::component('marketing-suite::landing-page-components.lead-form', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\LeadForm::class);
        Livewire::component('marketing-suite::landing-page-components.pricing-table', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\PricingTable::class);
        Livewire::component('marketing-suite::landing-page-components.icon-list-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\IconListSection::class);
        Livewire::component('marketing-suite::landing-page-components.countdown-timer', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\CountdownTimer::class);
        Livewire::component('marketing-suite::landing-page-components.event-registration', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\EventRegistration::class);
        Livewire::component('marketing-suite::landing-page-components.newsletter-signup', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\NewsletterSignup::class);
        Livewire::component('marketing-suite::landing-page-components.trust-indicators-section', \VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\TrustIndicatorsSection::class);
    }
}
