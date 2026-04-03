<div class="border-y border-gray-200 bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($title)
            <h2 class="mb-10 text-center text-2xl font-bold tracking-tight text-[#003879]">{{ $title }}</h2>
        @endif

        @if(!empty($indicators))
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-{{ min(count($indicators), 4) }}">
                @foreach($indicators as $indicator)
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#1565C0]/10">
                            @if(!empty($indicator['icon']))
                                <span class="text-2xl text-[#1565C0]">{!! $indicator['icon'] !!}</span>
                            @else
                                <svg class="h-7 w-7 text-[#1565C0]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <h3 class="mt-4 text-sm font-semibold text-[#003879]">{{ $indicator['title'] ?? '' }}</h3>
                        @if(!empty($indicator['description']))
                            <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $indicator['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
