@include('marketing-suite::livewire.landing-page-components.challenges-section', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'challenges' => $challenges ?? [],
])
