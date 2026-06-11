<?php

use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Services\LandingPageAiGenerator;

it('formats AI sections as a list with defaults applied', function () {
    $formatted = (new LandingPageAiGenerator)->formatSections([
        ['type' => 'hero_section', 'data' => ['title' => 'Big Launch']],
        ['type' => 'lead_form', 'data' => []],
        ['data' => ['title' => 'no type — skipped']],
        'junk',
    ]);

    expect(array_is_list($formatted))->toBeTrue()
        ->and($formatted)->toHaveCount(2)
        ->and($formatted[0]['type'])->toBe('hero_section')
        ->and($formatted[1]['data']['buttonText'])->not->toBeEmpty()
        ->and($formatted[1]['data']['fields'])->toHaveCount(2);
});

it('stores AI-generated sections readable by the edit form and the public page', function () {
    runPackageMigrations();

    $formatted = (new LandingPageAiGenerator)->formatSections([
        ['type' => 'hero_section', 'data' => ['title' => 'Big Launch', 'subtitle' => 'Sub']],
    ]);

    $page = LandingPage::create([
        'title' => ['en' => 'AI Page', 'bg' => 'AI Страница'],
        'slug' => 'ai-page',
        'goal_type' => 'lead_generation',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
    ]);

    // The same write EditLandingPage performs after generation.
    $page->update(['sections' => $formatted]);

    // Filament fills the edit form from attributesToArray() — the Builder
    // needs the plain list there, not a locale map, or the edit page shows
    // no sections and saving wipes them.
    expect($page->refresh()->sections)->toBe($formatted)
        ->and($page->attributesToArray()['sections'])->toBe($formatted);

    $this->get('/landing/ai-page')->assertOk()->assertSee('Big Launch');
});

it('constrains AI output with a schema covering every section type', function () {
    $schema = (new LandingPageAiGenerator)->sectionsSchema();

    $variants = collect($schema['properties']['sections']['items']['anyOf']);

    expect($variants)->toHaveCount(14)
        ->and($variants->map(fn ($variant) => $variant['properties']['type']['const'])->all())
        ->toContain('hero_section', 'lead_form', 'event_registration', 'pricing_table')
        ->and(json_encode($schema))->toBeString();
});
