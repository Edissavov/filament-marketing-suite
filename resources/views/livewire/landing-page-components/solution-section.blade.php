<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        @if($title || $subtitle)
            <div class="mx-auto mb-14 max-w-2xl text-center">
                @if($title)
                    <h2 class="text-3xl font-bold tracking-tight text-[#003879] sm:text-4xl">
                        {{ $title }}
                    </h2>
                @endif
                @if($subtitle)
                    <p class="mt-4 text-lg text-gray-600">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Steps --}}
        @if(count($steps) > 0)
            <div class="relative">
                {{-- Connecting line (desktop) --}}
                @if(count($steps) > 1)
                    <div class="absolute left-0 right-0 top-8 hidden h-0.5 bg-gradient-to-r from-[#1565C0]/20 via-[#1565C0]/40 to-[#1565C0]/20 lg:block" style="left: calc(100% / {{ count($steps) }} / 2); right: calc(100% / {{ count($steps) }} / 2);"></div>
                @endif

                <div class="grid gap-8 lg:grid-cols-{{ min(count($steps), 4) }}">
                    @foreach($steps as $index => $step)
                        <div class="relative flex flex-col items-center text-center lg:items-center">
                            {{-- Number bubble --}}
                            <div class="relative z-10 mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#1565C0] text-xl font-bold text-white shadow-md ring-4 ring-white">
                                {{ $step['number'] ?? $index + 1 }}
                            </div>

                            {{-- Step content --}}
                            <div class="rounded-xl border border-[#1565C0]/10 bg-[#1565C0]/5 p-5 w-full">
                                @if(!empty($step['title']))
                                    <h3 class="mb-2 text-base font-semibold text-[#003879]">
                                        {{ $step['title'] }}
                                    </h3>
                                @endif
                                @if(!empty($step['description']))
                                    <p class="text-sm leading-relaxed text-gray-600">
                                        {{ $step['description'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Benefits list --}}
        @if(count($benefits) > 0)
            <div class="mt-14 rounded-xl border border-green-100 bg-green-50 p-8">
                <ul class="grid gap-3 sm:grid-cols-2">
                    @foreach($benefits as $benefit)
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ is_array($benefit) ? ($benefit['text'] ?? '') : $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</section>
