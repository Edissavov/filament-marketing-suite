@php
    $previewHtml = view('marketing-suite::livewire.landing-page-components.newsletter-signup', [
        'title' => $title ?? '',
        'subtitle' => $subtitle ?? '',
        'buttonText' => $buttonText ?? '',
        'successMessage' => $successMessage ?? '',
        'privacyText' => $privacyText ?? '',
        'eventId' => null,
        'landingPageId' => null,
        'email' => '',
        'submitted' => false,
        'errors' => $errors ?? new \Illuminate\Support\ViewErrorBag(),
    ])->render();

    // A real <form> cannot be nested inside the panel's create/edit <form>:
    // the parser drops the inner tag, and its required inputs then block
    // native submission of the outer form.
    $previewHtml = str_replace(['<form', '</form>', 'type="submit"'], ['<div', '</div>', 'type="button"'], $previewHtml);
    $previewHtml = preg_replace('/\srequired(?=[\s>\/])/', '', $previewHtml);
@endphp

{!! $previewHtml !!}
