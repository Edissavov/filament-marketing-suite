<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire;

use VasilGerginski\MarketingSuite\Models\LandingPage as LandingPageModel;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.landing')]
class LandingPage extends Component
{
    public LandingPageModel $landingPage;

    /** @var array<int, array{type: string, data: array<string, mixed>}> */
    public array $sections = [];

    public function mount(string $slug): void
    {
        $query = LandingPageModel::query();

        if (! request()->has('preview')) {
            $query->where('is_active', true);
        }

        $this->landingPage = $query->where('slug', $slug)->firstOrFail();

        $this->loadSections();
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page', [
            'pageTitle' => $this->landingPage->title,
            'metaDescription' => $this->landingPage->meta_description,
            'ogImage' => $this->landingPage->og_image,
            'trackingCode' => $this->landingPage->enable_analytics ? $this->landingPage->tracking_code : null,
        ]);
    }

    private function loadSections(): void
    {
        $rawSections = $this->landingPage->sections ?? [];

        $this->sections = [];

        /** @var array<string, string> $sectionTypeMap */
        $sectionTypeMap = [
            'hero_section' => 'landing-page-components.hero-section',
            'challenges_section' => 'landing-page-components.challenges-section',
            'solution_section' => 'landing-page-components.solution-section',
            'product_showcase' => 'landing-page-components.product-showcase',
            'testimonials_section' => 'landing-page-components.testimonials-section',
            'faq_section' => 'landing-page-components.faq-section',
            'cta_section' => 'landing-page-components.cta-section',
            'lead_form' => 'landing-page-components.lead-form',
            'pricing_table' => 'landing-page-components.pricing-table',
            'icon_list_section' => 'landing-page-components.icon-list-section',
            'countdown_timer' => 'landing-page-components.countdown-timer',
            'event_registration' => 'landing-page-components.event-registration',
            'newsletter_signup' => 'landing-page-components.newsletter-signup',
            'trust_indicators' => 'landing-page-components.trust-indicators-section',
        ];

        foreach ($rawSections as $section) {
            $type = $section['type'] ?? null;

            if (! $type || ! isset($sectionTypeMap[$type])) {
                continue;
            }

            $data = $section['data'] ?? [];

            // Normalize array fields
            foreach ([
                'benefits',
                'features',
                'statistics',
                'buttons',
                'items',
                'steps',
                'plans',
                'questions',
                'testimonials',
                'indicators',
                'challenges',
                'products',
                'fields',
            ] as $field) {
                if (! (isset($data[$field]) && is_string($data[$field]))) {
                    continue;
                }

                $data[$field] = json_decode($data[$field], true) ?? [];
            }

            // Remove admin-only generator fields
            foreach ([
                'slot_start_date',
                'slot_end_date',
                'slot_day_start',
                'slot_day_end',
                'slot_duration',
                'slot_capacity',
                'slot_skip_weekends',
                'slots_json',
            ] as $adminField) {
                unset($data[$adminField]);
            }

            // Remove slots from props — too large for Livewire mount params
            // The EventRegistration component loads them from the landing page directly
            if (! request()->has('preview')) {
                unset($data['slots']);
            }

            // Pass event and landing page IDs for conversion tracking
            if ($this->landingPage->event_id) {
                $data['eventId'] = $this->landingPage->event_id;
                $data['landingPageId'] = $this->landingPage->id;
            }

            // Map 'slots' to 'availableSlots' for the EventRegistration component
            if (isset($data['slots'])) {
                $data['availableSlots'] = $data['slots'];
                unset($data['slots']);
            }

            $this->sections[] = [
                'type' => $sectionTypeMap[$type],
                'data' => $data,
            ];
        }

        if (empty($this->sections)) {
            abort(404);
        }
    }
}
