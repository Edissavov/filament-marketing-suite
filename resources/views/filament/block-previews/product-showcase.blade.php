@include('marketing-suite::livewire.landing-page-components.product-showcase', [
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'products' => $products ?? [],
])
