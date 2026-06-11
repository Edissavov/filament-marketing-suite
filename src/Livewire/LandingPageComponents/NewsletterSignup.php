<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use VasilGerginski\MarketingSuite\Jobs\SyncSubscriberToMailerLiteJob;
use VasilGerginski\MarketingSuite\Models\EventSubmission;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Models\NewsletterSubscriber;

class NewsletterSignup extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    public ?string $buttonText = '';

    public ?string $successMessage = '';

    public ?string $privacyText = '';

    public ?int $eventId = null;

    public ?int $landingPageId = null;

    public string $email = '';

    public bool $submitted = false;

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        ?string $buttonText = 'Subscribe',
        ?string $successMessage = 'Thank you for subscribing!',
        ?string $privacyText = '',
        ?int $eventId = null,
        ?int $landingPageId = null,
    ): void {
        $this->eventId = $eventId;
        $this->landingPageId = $landingPageId;
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->buttonText = $buttonText ?? 'Subscribe';
        $this->successMessage = $successMessage ?? 'Thank you for subscribing!';
        $this->privacyText = $privacyText ?? '';
    }

    public function subscribe(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $exists = NewsletterSubscriber::where('email', $this->email)->exists();

        if (! $exists) {
            NewsletterSubscriber::create([
                'email' => $this->email,
                'subscribed_at' => now(),
            ]);
        }

        if ($this->eventId || $this->landingPageId) {
            EventSubmission::create([
                'event_id' => $this->eventId,
                'landing_page_id' => $this->landingPageId,
                'short_url_visit_id' => session('short_url_visit_id'),
                'section_type' => 'newsletter_signup',
                'data' => ['email' => $this->email],
            ]);
        }

        $this->syncToMailerLite();

        $this->submitted = true;
    }

    private function syncToMailerLite(): void
    {
        if (! $this->landingPageId || empty($this->email)) {
            return;
        }

        $groupId = LandingPage::query()
            ->where('id', $this->landingPageId)
            ->whereNotNull('mailerlite_group_id')
            ->value('mailerlite_group_id');

        if ($groupId) {
            SyncSubscriberToMailerLiteJob::dispatch($groupId, $this->email);
        }
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.newsletter-signup');
    }
}
