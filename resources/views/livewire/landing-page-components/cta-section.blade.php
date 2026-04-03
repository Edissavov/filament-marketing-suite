<div id="cta" class="bg-[#1565C0] py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16">
            {{-- Left: Title, features, CTA --}}
            <div class="flex flex-col justify-center">
                @if ($title)
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="mt-4 text-lg text-blue-100">{{ $subtitle }}</p>
                @endif

                @if ($features)
                    <ul class="mt-8 space-y-3">
                        @foreach ($features as $feature)
                            <li wire:key="cta-feature-{{ $loop->index }}" class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#FFB60F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-base text-blue-50">{{ is_array($feature) ? ($feature['text'] ?? '') : $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($buttonText && $buttonLink)
                    <div class="mt-10">
                        <a
                            href="{{ $buttonLink }}"
                            class="inline-flex items-center rounded-full bg-white px-8 py-3.5 text-base font-semibold text-[#1565C0] shadow-lg transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#1565C0]"
                        >
                            {{ $buttonText }}
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Right: Testimonial quote --}}
            @if ($testimonial && isset($testimonial['content']))
                <div class="mt-12 flex items-center lg:mt-0">
                    <div class="rounded-xl bg-white/10 p-8 backdrop-blur-sm ring-1 ring-white/20">
                        <svg class="mb-4 h-8 w-8 text-white/30" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                        </svg>
                        <p class="text-lg font-medium italic leading-relaxed text-white">{{ $testimonial['content'] }}</p>
                        <div class="mt-6 border-t border-white/20 pt-6">
                            <p class="font-semibold text-white">{{ $testimonial['name'] ?? '' }}</p>
                            @if (isset($testimonial['role']) && $testimonial['role'])
                                <p class="mt-0.5 text-sm text-blue-200">{{ $testimonial['role'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
