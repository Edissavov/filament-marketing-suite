<div>
    {{-- Hero --}}
    <section class="bg-[#1565C0] px-5 py-20 sm:px-8 sm:py-28 lg:px-10">
        <div class="mx-auto max-w-7xl text-center">
            <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ __('News & Analysis') }}
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg font-light text-white/80">
                {{ __('Invest informed - follow the latest trends.') }}
            </p>
        </div>
    </section>

    {{-- Posts grid --}}
    <section class="bg-apple-gray px-5 py-16 sm:px-8 sm:py-20 lg:px-10">
        <div class="mx-auto max-w-7xl">
            @if($posts->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts as $post)
                        <article class="group flex flex-col overflow-hidden rounded-2xl border border-[#e0e0e0] bg-white transition-all duration-300 hover:border-[#c8c8c8] hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)]">
                            @if($post->image)
                                <a href="{{ route('blog.show', $post) }}" wire:navigate class="block aspect-video overflow-hidden">
                                    <img src="{{ asset($post->image) }}"
                                         alt="{{ $post->title }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         loading="lazy">
                                </a>
                            @endif
                            <div class="flex flex-1 flex-col p-8">
                                <time class="text-xs font-medium tracking-widest uppercase text-apple-secondary">
                                    {{ $post->published_at?->format('d.m.Y') }}
                                </time>
                                <h2 class="mt-4 text-lg font-semibold leading-snug tracking-tight text-[#1d1d1f] line-clamp-2 transition-colors duration-200 group-hover:text-[#1565C0]">
                                    <a href="{{ route('blog.show', $post) }}" wire:navigate>
                                        {{ $post->title }}
                                        <span class="absolute inset-0"></span>
                                    </a>
                                </h2>
                                <p class="mt-3 text-sm font-light leading-relaxed text-apple-secondary line-clamp-3">
                                    {{ Str::limit(strip_tags($post->content), 150) }}
                                </p>
                                <div class="mt-auto pt-8 flex items-center gap-2 text-sm font-semibold text-[#1565C0]">
                                    {{ __('Read more') }}
                                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-[#c8c8c8] bg-white p-20 text-center">
                    <p class="text-base font-light text-apple-secondary">{{ __('Our first publications are coming soon.') }}</p>
                </div>
            @endif
        </div>
    </section>
</div>
