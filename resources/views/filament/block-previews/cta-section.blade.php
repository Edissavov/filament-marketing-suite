@include('marketing-suite::livewire.landing-page-components.cta-section', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'buttonText' => $buttonText ?? '',
    'buttonLink' => $buttonLink ?? '',
    'features' => $features ?? [],
    'testimonial' => $testimonial ?? [],
])
