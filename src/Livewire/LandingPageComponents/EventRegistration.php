<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use VasilGerginski\MarketingSuite\Jobs\SyncSubscriberToMailerLiteJob;
use VasilGerginski\MarketingSuite\Models\EventSubmission;
use VasilGerginski\MarketingSuite\Models\LandingPage;

class EventRegistration extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    public ?string $eventDate = '';

    public ?string $eventTime = '';

    public ?string $eventLocation = '';

    /** @var array<int, array{name: string, label: string, type: string, required: bool}> */
    public array $fields = [];

    public ?string $buttonText = '';

    public ?string $successMessage = '';

    public ?int $eventId = null;

    public ?int $landingPageId = null;

    /** @var array<string, string> */
    public array $formData = [];

    public bool $submitted = false;

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        ?string $eventDate = '',
        ?string $eventTime = '',
        ?string $eventLocation = '',
        array $fields = [],
        ?string $buttonText = 'Register',
        ?string $successMessage = 'You have successfully registered for the event.',
        ?int $eventId = null,
        ?int $landingPageId = null,
        array $availableSlots = [],
    ): void {
        $this->eventId = $eventId;
        $this->landingPageId = $landingPageId;
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->eventDate = $eventDate ?? '';
        $this->eventTime = $eventTime ?? '';
        $this->eventLocation = $eventLocation ?? '';
        $this->fields = $fields;
        $this->buttonText = $buttonText ?? 'Register';
        $this->successMessage = $successMessage ?? 'You have successfully registered for the event.';

        foreach ($this->fields as $field) {
            $this->formData[$field['name']] = '';
        }
    }

    public function register(): void
    {
        $rules = [];

        foreach ($this->fields as $field) {
            $fieldRules = [];

            if ($field['required']) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field['type'] === 'email') {
                $fieldRules[] = 'email';
            }

            $rules['formData.' . $field['name']] = $fieldRules;
        }

        if (! empty($rules)) {
            $this->validate($rules);
        }

        if ($this->eventId) {
            EventSubmission::create([
                'event_id' => $this->eventId,
                'landing_page_id' => $this->landingPageId,
                'short_url_visit_id' => session('short_url_visit_id'),
                'section_type' => 'event_registration',
                'data' => $this->formData,
            ]);
        }

        $this->syncToMailerLite($this->formData);

        $this->submitted = true;
    }

    /**
     * @param  array<string, string>  $submissionData
     */
    private function syncToMailerLite(array $submissionData): void
    {
        if (! $this->landingPageId || empty($submissionData['email'])) {
            return;
        }

        $groupId = LandingPage::query()
            ->where('id', $this->landingPageId)
            ->whereNotNull('mailerlite_group_id')
            ->value('mailerlite_group_id');

        if ($groupId) {
            SyncSubscriberToMailerLiteJob::dispatch(
                $groupId,
                $submissionData['email'],
                array_filter([
                    'name' => $submissionData['name'] ?? null,
                    'phone' => $submissionData['phone'] ?? null,
                ]),
            );
        }
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.event-registration');
    }
}
