@include('marketing-suite::livewire.landing-page-components.faq-section', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'questions' => $questions ?? [],
    'ctaText' => $ctaText ?? '',
    'ctaLink' => $ctaLink ?? '',
])
