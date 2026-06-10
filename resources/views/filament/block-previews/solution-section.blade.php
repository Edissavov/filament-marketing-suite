@include('marketing-suite::livewire.landing-page-components.solution-section', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'steps' => $steps ?? [],
    'benefits' => $benefits ?? [],
])
