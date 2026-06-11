<?php

use Livewire\Livewire;
use VasilGerginski\MarketingSuite\Livewire\LandingPage as LandingPageComponent;
use VasilGerginski\MarketingSuite\Livewire\LandingPageComponents\LeadForm;
use VasilGerginski\MarketingSuite\Models\EventSubmission;
use VasilGerginski\MarketingSuite\Models\LandingPage;

it('auto-creates a linked tracking event when a landing page is created', function () {
    runPackageMigrations();

    $page = LandingPage::create([
        'title' => ['en' => 'Webinar Page', 'bg' => 'Уебинар'],
        'slug' => 'webinar-page',
        'goal_type' => 'newsletter',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
    ]);

    $page->refresh();

    expect($page->event_id)->not->toBeNull()
        ->and($page->event->name)->toBe('Webinar Page')
        ->and($page->event->event_type)->toBe('newsletter')
        ->and($page->event->is_active)->toBeTrue();
});

it('captures leads even when the landing page has no linked event', function () {
    runPackageMigrations();

    $page = LandingPage::create([
        'title' => ['en' => 'Leads Page', 'bg' => 'Лийдове'],
        'slug' => 'leads-page',
        'goal_type' => 'lead_generation',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
    ]);

    Livewire::test(LeadForm::class, [
        'title' => 'Get in touch',
        'fields' => [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
        ],
        'landingPageId' => $page->id,
    ])
        ->set('formData.name', 'Jane Doe')
        ->set('formData.email', 'jane@example.com')
        ->call('submitForm')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $lead = EventSubmission::sole();

    expect($lead->event_id)->toBeNull()
        ->and($lead->landing_page_id)->toBe($page->id)
        ->and($lead->data['email'])->toBe('jane@example.com');
});

it('passes the landing page id to sections even without a linked event', function () {
    runPackageMigrations();

    $page = LandingPage::create([
        'title' => ['en' => 'Leads Page', 'bg' => 'Лийдове'],
        'slug' => 'leads-page',
        'goal_type' => 'lead_generation',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
        'sections' => [
            [
                'type' => 'lead_form',
                'data' => [
                    'title' => 'Get in touch',
                    'fields' => [
                        ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                    ],
                ],
            ],
        ],
    ]);

    Livewire::test(LandingPageComponent::class, ['slug' => 'leads-page'])
        ->assertSet('sections.0.data.landingPageId', $page->id);
});
