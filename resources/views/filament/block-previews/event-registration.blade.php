@php
    $previewHtml = view('marketing-suite::livewire.landing-page-components.event-registration', [
        'title' => $title ?? '',
        'subtitle' => $subtitle ?? '',
        'eventDate' => $eventDate ?? '',
        'eventTime' => $eventTime ?? '',
        'eventLocation' => $eventLocation ?? '',
        'fields' => $fields ?? [],
        'buttonText' => $buttonText ?? '',
        'successMessage' => $successMessage ?? '',
        'eventId' => null,
        'landingPageId' => null,
        'submitted' => false,
        'formData' => [],
        'availableSlots' => [],
        'slots' => [],
    ])->render();

    // A real <form> cannot be nested inside the panel's create/edit <form>:
    // the parser drops the inner tag, and its required inputs then block
    // native submission of the outer form.
    $previewHtml = str_replace(['<form', '</form>', 'type="submit"'], ['<div', '</div>', 'type="button"'], $previewHtml);
    $previewHtml = preg_replace('/\srequired(?=[\s>\/])/', '', $previewHtml);
@endphp

{!! $previewHtml !!}
