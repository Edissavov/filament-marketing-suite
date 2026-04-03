<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire;

use VasilGerginski\MarketingSuite\Jobs\SyncSubscriberToMailerLiteJob;
use VasilGerginski\MarketingSuite\Models\NewsletterSubscriber;
use VasilGerginski\MarketingSuite\Settings\SiteSettings;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public string $email = '';

    public bool $submitted = false;

    public string $error = '';

    public function subscribe(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $exists = NewsletterSubscriber::where('email', $this->email)->exists();

        if (! $exists) {
            NewsletterSubscriber::create([
                'email' => $this->email,
            ]);
        }

        $this->syncToMailerLite();

        $this->submitted = true;
    }

    private function syncToMailerLite(): void
    {
        $settings = app(SiteSettings::class);
        $groupId = $settings->newsletter_group_id ?? null;

        if (! $groupId || empty($this->email)) {
            return;
        }

        SyncSubscriberToMailerLiteJob::dispatch($groupId, $this->email);
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.newsletter-subscribe');
    }
}
