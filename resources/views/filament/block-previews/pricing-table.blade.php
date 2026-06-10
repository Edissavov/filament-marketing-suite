@include('marketing-suite::livewire.landing-page-components.pricing-table', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'plans' => $plans ?? [],
])
