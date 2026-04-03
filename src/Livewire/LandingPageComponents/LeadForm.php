<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use VasilGerginski\MarketingSuite\Jobs\SyncSubscriberToMailerLiteJob;
use VasilGerginski\MarketingSuite\Models\EventSubmission;
use VasilGerginski\MarketingSuite\Models\LandingPage;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LeadForm extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{name: string, label: string, type: string, required: bool}> */
    public array $fields = [];

    public ?string $buttonText = '';

    public ?string $successMessage = '';

    public ?int $eventId = null;

    public ?int $landingPageId = null;

    public bool $submitted = false;

    /** @var array<string, string> */
    public array $formData = [];

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $fields = [],
        ?string $buttonText = '',
        ?string $successMessage = '',
        ?int $eventId = null,
        ?int $landingPageId = null,
    ): void {
        $this->eventId = $eventId;
        $this->landingPageId = $landingPageId;
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->fields = $fields;
        $this->buttonText = $buttonText ?? '';
        $this->successMessage = $successMessage ?? '';

        foreach ($this->fields as $field) {
            $this->formData[$field['name']] = '';
        }
    }

    public function submitForm(): void
    {
        $rules = [];

        foreach ($this->fields as $field) {
            $rule = $field['required'] ? 'required' : 'nullable';

            if ($field['type'] === 'email') {
                $rule .= '|email';
            }

            $rules['formData.'.$field['name']] = $rule;
        }

        if (! empty($rules)) {
            $this->validate($rules);
        }

        if ($this->eventId) {
            EventSubmission::create([
                'event_id' => $this->eventId,
                'landing_page_id' => $this->landingPageId,
                'short_url_visit_id' => session('short_url_visit_id'),
                'section_type' => 'lead_form',
                'data' => $this->formData,
            ]);
        }

        $this->syncToMailerLite($this->formData);

        $this->dispatch('lead-form-submitted', data: $this->formData);

        $this->submitted = true;
        $this->reset('formData');

        foreach ($this->fields as $field) {
            $this->formData[$field['name']] = '';
        }
    }

    /**
     * @param  array<string, string>  $formData
     */
    private function syncToMailerLite(array $formData): void
    {
        if (! $this->landingPageId || empty($formData['email'])) {
            return;
        }

        $groupId = LandingPage::query()
            ->where('id', $this->landingPageId)
            ->whereNotNull('mailerlite_group_id')
            ->value('mailerlite_group_id');

        if ($groupId) {
            SyncSubscriberToMailerLiteJob::dispatch(
                $groupId,
                $formData['email'],
                array_filter([
                    'name' => $formData['name'] ?? null,
                    'phone' => $formData['phone'] ?? null,
                ]),
            );
        }
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.lead-form');
    }
}
