@include('marketing-suite::livewire.landing-page-components.hero-section', [
    'backgroundImage' => $backgroundImage ?? '',
    'badge' => $badge ?? '',
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'buttons' => $buttons ?? [],
    'statistics' => $statistics ?? [],
])
