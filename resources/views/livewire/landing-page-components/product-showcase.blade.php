<section class="bg-[#F5F5F5] py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        @if($title || $subtitle)
            <div class="mx-auto mb-12 max-w-2xl text-center">
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

        {{-- Product cards --}}
        @if(count($products) > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-{{ min(count($products), 3) }}">
                @foreach($products as $product)
                    <div class="group flex flex-col overflow-hidden rounded-xl bg-white shadow-sm transition hover:shadow-md">

                        {{-- Product image --}}
                        @if(!empty($product['image']))
                            <div class="h-48 overflow-hidden bg-[#1565C0]/5">
                                <img
                                    src="{{ $product['image'] }}"
                                    alt="{{ $product['name'] ?? '' }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                >
                            </div>
                        @else
                            <div class="flex h-48 items-center justify-center bg-[#1565C0]/5">
                                <svg class="h-16 w-16 text-[#1565C0]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Card body --}}
                        <div class="flex flex-1 flex-col p-6">
                            {{-- Name --}}
                            @if(!empty($product['name']))
                                <h3 class="text-lg font-semibold text-[#003879]">
                                    {{ $product['name'] }}
                                </h3>
                            @endif

                            {{-- Description --}}
                            @if(!empty($product['description']))
                                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                                    {{ $product['description'] }}
                                </p>
                            @endif

                            {{-- Features list --}}
                            @if(!empty($product['features']) && count($product['features']) > 0)
                                <ul class="mt-5 space-y-2">
                                    @foreach($product['features'] as $feature)
                                        <li class="flex items-center gap-2.5 text-sm text-gray-700">
                                            <svg class="h-4 w-4 shrink-0 text-[#1565C0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ is_array($feature) ? ($feature['text'] ?? '') : $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- CTA link --}}
                            @if(!empty($product['link']))
                                <div class="mt-auto pt-6">
                                    <a
                                        href="{{ $product['link'] }}"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-[#1565C0] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#003879] focus:outline-none focus:ring-2 focus:ring-[#1565C0] focus:ring-offset-2"
                                    >
                                        {{ __('Learn more') }}
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
