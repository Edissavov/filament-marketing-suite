<?php

use Filament\Facades\Filament;
use Illuminate\Foundation\Auth\User as AuthUser;
use Livewire\Livewire;
use VasilGerginski\MarketingSuite\Filament\Resources\LandingPageResource\Pages\EditLandingPage;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use VasilGerginski\MarketingSuite\Services\LandingPageAiGenerator;

/**
 * Shaped like real tool-call output: array entries are complete (the schema
 * requires every key on array items) but optional top-level scalars can be
 * omitted by the model — the countdown timer below has no targetDate.
 */
function aiGeneratedTenSections(): array
{
    return (new LandingPageAiGenerator)->formatSections([
        ['type' => 'hero_section', 'data' => ['title' => 'Hero', 'subtitle' => 'S', 'badge' => 'New', 'buttons' => [['text' => 'Go', 'link' => '#lead-form', 'style' => 'primary']], 'statistics' => [['value' => '12%', 'description' => 'Up']]]],
        ['type' => 'challenges_section', 'data' => ['title' => 'Challenges', 'subtitle' => 'S', 'challenges' => [['icon' => '', 'title' => 'C', 'description' => 'D']]]],
        ['type' => 'solution_section', 'data' => ['title' => 'Solution', 'subtitle' => 'S', 'steps' => [['number' => '1', 'title' => 'T', 'description' => 'D']], 'benefits' => [['text' => 'B']]]],
        ['type' => 'product_showcase', 'data' => ['title' => 'Products', 'subtitle' => 'S', 'products' => [['name' => 'P', 'description' => 'D', 'features' => [['text' => 'F']]]]]],
        ['type' => 'testimonials_section', 'data' => ['title' => 'Testimonials', 'subtitle' => 'S', 'testimonials' => [['name' => 'N', 'role' => 'R', 'content' => 'C', 'rating' => 5]]]],
        ['type' => 'faq_section', 'data' => ['title' => 'FAQ', 'subtitle' => 'S', 'questions' => [['question' => 'Q', 'answer' => 'A']]]],
        ['type' => 'cta_section', 'data' => ['title' => 'CTA', 'subtitle' => 'S', 'features' => [['text' => 'F']]]],
        ['type' => 'lead_form', 'data' => ['title' => 'Lead', 'subtitle' => 'S']],
        ['type' => 'countdown_timer', 'data' => ['title' => 'Countdown', 'subtitle' => 'S', 'buttonText' => 'Go', 'buttonLink' => '#register']],
        ['type' => 'newsletter_signup', 'data' => ['title' => 'News', 'subtitle' => 'S', 'privacyText' => 'P']],
    ]);
}

it('saves an AI-generated landing page with ten sections from the edit page', function () {
    runPackageMigrations();
    runShortUrlMigrations();

    $user = new class extends AuthUser
    {
        protected $table = 'users';

        protected $guarded = [];
    };
    $user->forceFill(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret'])->save();

    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    $page = LandingPage::create([
        'title' => ['en' => 'Big Page', 'bg' => 'Страница'],
        'slug' => 'big-page',
        'goal_type' => 'lead_generation',
        'theme' => 'blue',
        'template' => 'blank',
        'is_active' => true,
        'sections' => aiGeneratedTenSections(),
    ]);

    Livewire::test(EditLandingPage::class, ['record' => 'big-page'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($page->refresh()->sections)->toHaveCount(10);
});
