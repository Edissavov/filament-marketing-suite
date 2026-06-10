<div>
    @foreach($sections as $section)
        @livewire($section['type'], $section['data'], key($loop->index))
    @endforeach

    @if($trackingCode)
        {!! $trackingCode !!}
    @endif
</div>
