@include('marketing-suite::livewire.landing-page-components.testimonials-section', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'testimonials' => $testimonials ?? [],
])
