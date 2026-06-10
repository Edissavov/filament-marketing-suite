@include('marketing-suite::livewire.landing-page-components.countdown-timer', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'targetDate' => $targetDate ?? '',
    'buttonText' => $buttonText ?? '',
    'buttonLink' => $buttonLink ?? '',
])
