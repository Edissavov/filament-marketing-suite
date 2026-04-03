<div class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($title)
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-[#003879] sm:text-4xl">{{ $title }}</h2>
                @if($subtitle)
                    <p class="mt-4 text-lg text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        @if(!empty($items))
            <div class="mx-auto mt-12 grid max-w-5xl grid-cols-1 gap-8 sm:grid-cols-2">
                @foreach($items as $item)
                    <div class="flex items-start gap-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#1565C0]/10">
                            @if(!empty($item['icon']))
                                <span class="text-xl text-[#1565C0]">{!! $item['icon'] !!}</span>
                            @else
                                <svg class="h-6 w-6 text-[#1565C0]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-[#003879]">{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['description']))
                                <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
