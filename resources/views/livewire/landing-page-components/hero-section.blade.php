<section
    class="relative min-h-145 overflow-hidden bg-[#1565C0]"
    @if($backgroundImage)
        style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center;"
    @endif
>
    {{-- Overlay when background image is set --}}
    @if($backgroundImage)
        <div class="absolute inset-0 bg-[#1565C0]/80"></div>
    @endif

    {{-- Decorative circles --}}
    <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-white/5"></div>
    <div class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-white/5"></div>

    <div class="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">

            {{-- Badge --}}
            @if($badge)
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 backdrop-blur-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                    <span class="text-sm font-medium text-white">{{ $badge }}</span>
                </div>
            @endif

            {{-- Title (supports HTML for colored spans) --}}
            @if($title)
                <h1 class="text-4xl font-bold leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                    {!! $title !!}
                </h1>
            @endif

            {{-- Subtitle --}}
            @if($subtitle)
                <p class="mt-6 text-lg leading-relaxed text-blue-100 sm:text-xl">
                    {{ $subtitle }}
                </p>
            @endif

            {{-- Buttons --}}
            @if(count($buttons) > 0)
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    @foreach($buttons as $button)
                        @if(($button['style'] ?? 'primary') === 'primary')
                            <a
                                href="{{ $button['link'] ?? $button['url'] ?? '#' }}"
                                class="inline-flex min-w-45 items-center justify-center rounded-full bg-white px-8 py-3.5 text-base font-semibold text-[#1565C0] shadow-lg transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#1565C0]"
                            >
                                {{ $button['text'] ?? ($button['label'] ?? '') }}
                            </a>
                        @else
                            <a
                                href="{{ $button['link'] ?? $button['url'] ?? '#' }}"
                                class="inline-flex min-w-45 items-center justify-center rounded-full border border-white/40 bg-white/10 px-8 py-3.5 text-base font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#1565C0]"
                            >
                                {{ $button['text'] ?? ($button['label'] ?? '') }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Statistics row --}}
        @if(count($statistics) > 0)
            <div class="mt-16 border-t border-white/10 pt-10">
                <dl class="grid grid-cols-2 gap-6 sm:grid-cols-{{ min(count($statistics), 4) }}">
                    @foreach($statistics as $stat)
                        <div class="text-center">
                            <dt class="text-sm font-medium text-blue-200">{{ $stat['description'] ?? $stat['label'] ?? '' }}</dt>
                            <dd class="mt-1 text-3xl font-bold text-white">{{ $stat['value'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endif
    </div>
</section>
