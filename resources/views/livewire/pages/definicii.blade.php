<div>
    {{-- HERO --}}
    <section class="bg-white px-5 pt-24 pb-14 sm:px-8 sm:pt-40 sm:pb-28 lg:px-10">
        <div class="mx-auto max-w-4xl text-center" x-data="{ shown: false }" x-init="shown = false; $nextTick(() => setTimeout(() => shown = true, 80))">
            <p :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
               class="transition-all duration-700 ease-apple mb-4 text-xs font-semibold uppercase tracking-widest text-apple-secondary">
                {{ __('Glossary') }}
            </p>
            <h1 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                class="transition-all duration-700 delay-100 ease-apple text-3xl font-semibold tracking-[-0.02em] text-brand sm:text-4xl lg:text-5xl">
                {{ __('Definitions') }}
            </h1>
            <div class="mt-3 h-px bg-linear-to-r from-transparent via-brand/20 to-transparent"></div>
            <p :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
               class="transition-all duration-700 delay-200 ease-apple mx-auto mt-6 max-w-2xl text-lg font-light leading-relaxed text-apple-secondary">
                {{ __('Key terms and concepts in peer-to-peer investing to help you make informed investment decisions.') }}
            </p>
        </div>
    </section>

    {{-- DEFINITIONS GRID --}}
    <section class="bg-apple-gray px-5 py-14 sm:px-8 sm:py-32 lg:px-10">
        <div class="mx-auto max-w-7xl" x-data="{ shown: false }" x-intersect.margin.-100px="shown = true">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($definitions as $definition)
                    <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         class="transition-all duration-700 ease-apple group rounded-2xl bg-white p-8 border border-[#e0e0e0] shadow-sm hover:border-[#c8c8c8] hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)]"
                         style="transition-delay: {{ 80 * ($loop->index % 6) }}ms">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-brand/8 text-brand">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                        <h3 class="text-base font-semibold tracking-tight text-[#1d1d1f]">{{ $definition->term }}</h3>
                        <p class="mt-3 text-sm font-light leading-relaxed text-apple-secondary">{{ $definition->description }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Help Center CTA --}}
            <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 class="transition-all duration-700 delay-300 ease-apple mt-12 rounded-2xl border border-[#e0e0e0] bg-white p-8 text-center shadow-sm sm:p-10">
                <h3 class="text-xl font-semibold tracking-tight text-[#1d1d1f]">{{ __('Have more questions?') }}</h3>
                <p class="mx-auto mt-3 max-w-lg text-sm font-light leading-relaxed text-apple-secondary">
                    {{ __('Visit our Help Center for detailed guides on registration, investing, deposits, withdrawals and more.') }}
                </p>
                <a href="{{ route('marketing-suite.help') }}" wire:navigate
                   class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-brand/90">
                    {{ __('Help Center') }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- INVESTMENT CALCULATOR (rendered only when the host app provides the component) --}}
    @if (\Livewire\Livewire::exists('investment-calculator'))
    <section class="bg-white px-5 py-14 sm:px-8 sm:py-32 lg:px-10">
        <div class="mx-auto max-w-5xl" x-data="{ shown: false }" x-intersect.margin.-100px="shown = true">
            <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 class="transition-all duration-700 ease-apple text-center max-w-3xl mx-auto mb-10 sm:mb-16">
                <h2 class="text-3xl font-semibold tracking-[-0.02em] text-brand sm:text-4xl lg:text-5xl">
                    {{ __('Investment Calculator') }}
                </h2>
                <p class="mt-4 text-lg font-light leading-relaxed text-apple-secondary">
                    {{ __('Calculate the potential return on your investment.') }}
                </p>
            </div>
            <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 class="transition-all duration-700 delay-150 ease-apple overflow-hidden rounded-2xl border border-[#e0e0e0] bg-white p-8 shadow-sm sm:p-10">
                <livewire:investment-calculator />
            </div>
        </div>
    </section>
    @endif

    {{-- RELATED BLOG POSTS --}}
    <section class="bg-apple-gray px-5 py-14 sm:px-8 sm:py-32 lg:px-10">
        <div class="mx-auto max-w-7xl" x-data="{ shown: false }" x-intersect.margin.-100px="shown = true">
            <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 class="transition-all duration-700 ease-apple flex flex-col items-center text-center sm:flex-row sm:items-end sm:justify-between sm:text-left mb-14">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-semibold tracking-[-0.02em] text-brand sm:text-4xl lg:text-5xl">
                        {{ __('Related Articles') }}
                    </h2>
                    <p class="mt-4 text-lg font-light leading-relaxed text-apple-secondary">
                        {{ __('Deepen your knowledge with our latest articles.') }}
                    </p>
                </div>
                <a href="{{ route('marketing-suite.blog') }}" wire:navigate
                   class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all duration-200 hover:gap-3 sm:mt-0">
                    {{ __('All articles') }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($relatedPosts->isNotEmpty())
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($relatedPosts as $index => $post)
                        <article :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                                 class="transition-all duration-700 ease-apple group flex flex-col rounded-2xl bg-white border border-[#e0e0e0] shadow-sm hover:border-[#c8c8c8] hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] relative overflow-hidden"
                                 style="transition-delay: {{ 100 + ($index * 80) }}ms">
                            @if($post->image)
                                <a href="{{ route('marketing-suite.blog') }}" wire:navigate class="block aspect-video overflow-hidden">
                                    <img src="{{ $post->image_url }}"
                                         alt="{{ $post->title }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         loading="lazy">
                                </a>
                            @endif
                            <div class="flex flex-1 flex-col p-8">
                                <div class="flex-1">
                                    <time class="text-xs font-medium tracking-widest text-apple-secondary uppercase">{{ $post->published_at?->format('d.m.Y') }}</time>
                                    <h3 class="mt-4 text-lg font-semibold tracking-tight text-[#1d1d1f] line-clamp-2 leading-snug group-hover:text-brand transition-colors duration-200">
                                        <a href="{{ route('marketing-suite.blog') }}" wire:navigate>{{ $post->title }}<span class="absolute inset-0"></span></a>
                                    </h3>
                                    <p class="mt-3 text-sm font-light leading-relaxed text-apple-secondary line-clamp-3">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                </div>
                                <div class="mt-8 flex items-center gap-2 text-sm font-semibold text-brand">
                                    {{ __('Read more') }}
                                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-[#c8c8c8] bg-apple-gray p-16 text-center">
                    <p class="text-base font-light text-apple-secondary">{{ __('Our first publications are coming soon.') }}</p>
                </div>
            @endif
        </div>
    </section>

    <x-slot:structuredData>
        @include('marketing-suite::partials.structured-data', [
            'breadcrumbs' => [['name' => __('Definitions'), 'url' => route('marketing-suite.definitions')]],
        ])
    </x-slot:structuredData>
</div>
