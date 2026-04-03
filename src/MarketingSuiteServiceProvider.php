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
use VasilGerginski\MarketingSuite\Livewire\Blog\Index;
use VasilGerginski\MarketingSuite\Livewire\Blog\Show;
use VasilGerginski\MarketingSuite\Livewire\ContactForm;
use VasilGerginski\MarketingSuite\Livewire\HelpCenter;
use VasilGerginski\MarketingSuite\Livewire\HelpCenter\Search;
use VasilGerginski\MarketingSuite\Livewire\LandingPage;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\ChallengesSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\CountdownTimer;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\CtaSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\EventRegistration;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\FaqSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\HeroSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\IconListSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\LeadForm;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\NewsletterSignup;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\PricingTable;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\ProductShowcase;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\SolutionSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\TestimonialsSection;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\TrustIndicatorsSection;
use VasilGerginski\MarketingSuite\Livewire\NewsletterSubscribe;
use VasilGerginski\MarketingSuite\Livewire\Pages\Blog;
use VasilGerginski\MarketingSuite\Livewire\Pages\Definicii;
use VasilGerginski\MarketingSuite\Livewire\Pages\OurAuthors;
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
        Livewire::component('marketing-suite::blog.index', Index::class);
        Livewire::component('marketing-suite::blog.show', Show::class);
        Livewire::component('marketing-suite::landing-page', LandingPage::class);
        Livewire::component('marketing-suite::newsletter-subscribe', NewsletterSubscribe::class);
        Livewire::component('marketing-suite::help-center', HelpCenter::class);
        Livewire::component('marketing-suite::help-center.search', Search::class);
        Livewire::component('marketing-suite::contact-form', ContactForm::class);
        Livewire::component('marketing-suite::pages.blog', Blog::class);
        Livewire::component('marketing-suite::pages.our-authors', OurAuthors::class);
        Livewire::component('marketing-suite::pages.definicii', Definicii::class);

        // Landing Page Section Components
        Livewire::component('marketing-suite::landing-page-components.hero-section', HeroSection::class);
        Livewire::component('marketing-suite::landing-page-components.challenges-section', ChallengesSection::class);
        Livewire::component('marketing-suite::landing-page-components.solution-section', SolutionSection::class);
        Livewire::component('marketing-suite::landing-page-components.product-showcase', ProductShowcase::class);
        Livewire::component('marketing-suite::landing-page-components.testimonials-section', TestimonialsSection::class);
        Livewire::component('marketing-suite::landing-page-components.faq-section', FaqSection::class);
        Livewire::component('marketing-suite::landing-page-components.cta-section', CtaSection::class);
        Livewire::component('marketing-suite::landing-page-components.lead-form', LeadForm::class);
        Livewire::component('marketing-suite::landing-page-components.pricing-table', PricingTable::class);
        Livewire::component('marketing-suite::landing-page-components.icon-list-section', IconListSection::class);
        Livewire::component('marketing-suite::landing-page-components.countdown-timer', CountdownTimer::class);
        Livewire::component('marketing-suite::landing-page-components.event-registration', EventRegistration::class);
        Livewire::component('marketing-suite::landing-page-components.newsletter-signup', NewsletterSignup::class);
        Livewire::component('marketing-suite::landing-page-components.trust-indicators-section', TrustIndicatorsSection::class);
    }
}
