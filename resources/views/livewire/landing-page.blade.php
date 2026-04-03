@section('title', $pageTitle)

@if($metaDescription)
    @section('meta_description', $metaDescription)
@endif

@if($ogImage)
    @section('og_image', $ogImage)
@endif

<div>
    @foreach($sections as $section)
        @livewire($section['type'], $section['data'], key($loop->index))
    @endforeach

    @if($trackingCode)
        {!! $trackingCode !!}
    @endif
</div>
