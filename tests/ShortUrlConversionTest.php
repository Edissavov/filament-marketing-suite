<?php

use Livewire\Livewire;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\LeadForm;
use VasilGerginski\MarketingSuite\Models\EventSubmission;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Models\ShortUrl;

it('counts a conversion when a lead arrives through a short url', function () {
    runPackageMigrations();
    runShortUrlMigrations();

    $page = LandingPage::create([
        'title' => ['en' => 'Conversions', 'bg' => 'Конверсии'],
        'slug' => 'conversions',
        'goal_type' => 'lead_generation',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
    ]);

    $short = ShortUrl::query()->create([
        'destination_url' => $page->url,
        'url_key' => 'golden',
        'default_short_url' => ShortUrl::defaultShortUrlFor('golden'),
        'single_use' => false,
        'forward_query_params' => false,
        'track_visits' => true,
        'track_ip_address' => true,
        'track_browser' => true,
        'track_browser_version' => true,
        'track_operating_system' => true,
        'track_operating_system_version' => true,
        'track_referer_url' => true,
        'track_device_type' => true,
        'activated_at' => now(),
    ]);

    // Following the short link records the visit and stores its id in the
    // session for the landing page forms to pick up.
    $this->get('/s/golden')->assertRedirect($page->url);

    $visitId = session('short_url_visit_id');

    expect($visitId)->not->toBeNull();

    Livewire::test(LeadForm::class, [
        'fields' => [
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
        ],
        'landingPageId' => $page->id,
        'eventId' => $page->refresh()->event_id,
    ])
        ->set('formData.email', 'jane@example.com')
        ->call('submitForm')
        ->assertHasNoErrors();

    expect(EventSubmission::sole()->short_url_visit_id)->toBe($visitId)
        ->and($short->refresh()->conversion_count)->toBe(1);
});
