<div>
    {{-- Hero --}}
    <section class="relative bg-[#1565C0] px-5 pb-16 pt-10 sm:px-8 sm:pb-24 sm:pt-14 lg:px-10 overflow-hidden">
        <div class="pointer-events-none absolute -right-20 -top-20 h-80 w-80 rounded-full bg-white/[0.03]"></div>
        <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-white/[0.02]"></div>

        <div class="relative mx-auto max-w-5xl" x-data="{ shown: false }" x-init="shown = false; $nextTick(() => setTimeout(() => shown = true, 80))">
            <a href="{{ route('blog.index') }}" wire:navigate class="group inline-flex items-center gap-1.5 text-sm font-medium text-white/60 transition-colors duration-200 hover:text-white mb-10">
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Blog') }}
            </a>

            <h1 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                class="transition-all duration-700 delay-100 ease-apple text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ __('Our Authors') }}
            </h1>
            <p :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
               class="transition-all duration-700 delay-200 ease-apple mt-4 max-w-2xl text-base font-light text-white/60 leading-relaxed">
                {{ __('Meet the experts behind our content — professionals with deep knowledge in finance, investment, and P2P lending.') }}
            </p>
        </div>
    </section>

    {{-- Author profiles --}}
    <section class="bg-[#F5F5F5] px-5 py-12 sm:px-8 sm:py-16 lg:px-10">
        <div class="mx-auto max-w-5xl space-y-8">
            @forelse($authors as $author)
                <div x-data="{ shown: false }" x-intersect.margin.-50px="shown = true">
                    <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                         class="transition-all duration-700 ease-apple overflow-hidden rounded-2xl bg-white shadow-sm">

                        {{-- Author header --}}
                        <div class="flex flex-col items-center gap-6 p-8 sm:flex-row sm:items-start sm:gap-8 sm:p-10">
                            @if($author->image)
                                <div class="shrink-0">
                                    <img src="{{ Storage::url($author->image) }}" alt="{{ $author->name }}"
                                         class="h-24 w-24 rounded-full object-cover shadow-sm ring-4 ring-[#F5F5F5] sm:h-28 sm:w-28"
                                         loading="lazy">
                                </div>
                            @else
                                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-[#1565C0]/8 text-2xl font-bold text-[#1565C0] shadow-sm ring-4 ring-[#F5F5F5] sm:h-28 sm:w-28 sm:text-3xl">
                                    {{ mb_substr($author->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1 text-center sm:text-left">
                                <h2 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">{{ $author->name }}</h2>
                                @if($author->blogPosts->isNotEmpty())
                                    <p class="mt-1 text-xs font-medium uppercase tracking-widest text-[#1565C0]/60">
                                        {{ trans_choice(':count article|:count articles', $author->blogPosts->count(), ['count' => $author->blogPosts->count()]) }}
                                    </p>
                                @endif
                                @if($author->bio)
                                    <div class="mt-4 text-sm font-light leading-relaxed text-[#52525b] prose prose-sm max-w-none prose-p:text-[#52525b] prose-p:font-light">
                                        {!! $author->bio !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Author's articles --}}
                        @if($author->blogPosts->isNotEmpty())
                            <div class="border-t border-[#f0f0f0] px-8 py-8 sm:px-10">
                                <h3 class="mb-6 text-[11px] font-bold uppercase tracking-widest text-[#1565C0]/50">
                                    {{ __('Articles by') }} {{ $author->name }}
                                </h3>
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($author->blogPosts as $post)
                                        <x-blog-card :post="$post" variant="compact" />
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl bg-white p-16 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#1565C0]/8">
                        <svg class="h-7 w-7 text-[#1565C0]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <p class="text-base font-light text-[#52525b]">{{ __('Author profiles coming soon.') }}</p>
                </div>
            @endforelse
        </div>
    </section>

    <x-slot:structuredData>
        @include('partials.structured-data', [
            'breadcrumbs' => [['name' => __('Our Authors'), 'url' => route('our-authors')]],
        ])
    </x-slot:structuredData>
</div>
