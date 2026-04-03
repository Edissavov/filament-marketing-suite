<div class="bg-[#F5F5F5] py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if($title)
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-brand sm:text-4xl">{{ $title }}</h2>
                @if($subtitle)
                    <p class="mt-4 text-lg text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        @if(!empty($plans))
            <div class="mx-auto mt-12 grid max-w-5xl grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-{{ count($plans) }}">
                @foreach($plans as $plan)
                    <div @class([
                        'relative flex flex-col rounded-xl bg-white p-8 shadow-sm',
                        'ring-2 ring-[#1565C0]' => $plan['isPopular'] ?? false,
                        'ring-1 ring-gray-200' => !($plan['isPopular'] ?? false),
                    ])>
                        @if($plan['isPopular'] ?? false)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <span class="inline-flex items-center rounded-full bg-[#1565C0] px-4 py-1 text-xs font-semibold text-white">
                                    Популярен
                                </span>
                            </div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-brand">{{ $plan['name'] ?? '' }}</h3>
                            <div class="mt-4 flex items-baseline gap-1">
                                <span class="text-4xl font-bold tracking-tight text-[#1565C0]">{{ $plan['price'] ?? '' }}</span>
                                @if(!empty($plan['period']))
                                    <span class="text-sm text-gray-500">/ {{ $plan['period'] }}</span>
                                @endif
                            </div>
                        </div>

                        @if(!empty($plan['features']))
                            <ul class="mb-8 flex-1 space-y-3">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-start gap-3">
                                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#1565C0]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ is_array($feature) ? ($feature['text'] ?? '') : $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($plan['buttonText']) && !empty($plan['buttonLink']))
                            <a
                                href="{{ $plan['buttonLink'] }}"
                                @class([
                                    'mt-auto block rounded-lg px-6 py-3 text-center text-sm font-semibold transition',
                                    'bg-[#1565C0] text-white hover:bg-[#003879]' => $plan['isPopular'] ?? false,
                                    'bg-[#1565C0]/10 text-[#1565C0] hover:bg-[#1565C0]/20' => !($plan['isPopular'] ?? false),
                                ])
                            >
                                {{ $plan['buttonText'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
