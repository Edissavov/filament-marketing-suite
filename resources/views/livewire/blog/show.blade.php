<div>
    {{-- Hero --}}
    <section class="relative bg-[#1565C0] px-5 pb-16 pt-10 sm:px-8 sm:pb-24 sm:pt-12 lg:px-10 overflow-hidden">
        {{-- Subtle geometric accent --}}
        <div class="pointer-events-none absolute -right-20 -top-20 h-80 w-80 rounded-full bg-white/3"></div>
        <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-white/2"></div>

        <div class="relative mx-auto max-w-3xl">
            <a href="{{ route('marketing-suite.blog') }}" wire:navigate class="group inline-flex items-center gap-1.5 text-sm font-medium text-white/60 transition-colors duration-200 hover:text-white mb-10">
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('All articles') }}
            </a>

            <div class="flex flex-wrap items-center gap-4 mb-5 text-[11px] font-medium uppercase tracking-widest text-white/50">
                @if($post->category)
                    <span>{{ __(ucfirst($post->category)) }}</span>
                    <span class="text-white/20">|</span>
                @endif
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $readingTime }} {{ __('min read') }}
                </span>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-[2.75rem] lg:leading-[1.15]">
                {{ $post->title }}
            </h1>

            {{-- Author + date --}}
            <div class="mt-8 flex items-center gap-4">
                @if($post->author)
                    @if($post->author->image)
                        <img src="{{ Storage::url($post->author->image) }}" alt="{{ $post->author->name }}" class="h-11 w-11 rounded-full object-cover ring-2 ring-white/20 shadow-lg">
                    @else
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-sm font-bold text-white shadow-lg ring-2 ring-white/10">
                            {{ mb_substr($post->author->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="leading-tight">
                        <span class="block text-sm font-semibold text-white">{{ $post->author->name }}</span>
                        <time class="text-xs text-white/50">{{ $post->published_at?->translatedFormat('d M, Y') }}</time>
                    </div>
                @else
                    <time class="text-sm font-medium text-white/60">{{ $post->published_at?->translatedFormat('d M, Y') }}</time>
                @endif
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="bg-[#F5F5F5] px-5 py-12 sm:px-8 sm:py-16 lg:px-10">
        <div class="mx-auto max-w-3xl">
            @if($post->image)
                <div class="mb-10 sm:mb-14">
                    <img src="{{ $post->image_url }}"
                         alt="{{ $post->title }}"
                         class="w-full rounded-2xl object-cover aspect-2/1 shadow-sm"
                         loading="lazy">
                </div>
            @endif

            <article class="prose prose-lg max-w-none
                prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-[#1d1d1f]
                prose-h2:text-2xl prose-h2:mt-12 prose-h2:mb-4
                prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                prose-p:text-[#3a3a3c] prose-p:font-light prose-p:leading-[1.8]
                prose-a:text-[#1565C0] prose-a:font-medium prose-a:no-underline prose-a:border-b prose-a:border-[#1565C0]/30 hover:prose-a:border-[#1565C0]
                prose-strong:text-[#1d1d1f] prose-strong:font-semibold
                prose-blockquote:border-l-[#1565C0] prose-blockquote:bg-[#1565C0]/3 prose-blockquote:rounded-r-xl prose-blockquote:py-1 prose-blockquote:pr-6 prose-blockquote:not-italic prose-blockquote:text-[#3a3a3c]
                prose-img:rounded-xl prose-img:shadow-sm
                prose-li:text-[#3a3a3c] prose-li:font-light
                prose-hr:border-[#e0e0e0]">
                {!! $post->content !!}
            </article>

            {{-- Author bio --}}
            @if($post->author)
                <div class="mt-14 border-t border-[#e0e0e0] pt-10">
                    <div class="flex items-start gap-5">
                        @if($post->author->image)
                            <img src="{{ Storage::url($post->author->image) }}" alt="{{ $post->author->name }}" class="h-14 w-14 shrink-0 rounded-full object-cover shadow-sm">
                        @else
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#1565C0]/8 text-lg font-bold text-[#1565C0]">
                                {{ mb_substr($post->author->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-widest text-[#1565C0]/60">{{ __('Written by') }}</p>
                            <p class="mt-1 text-base font-semibold text-[#1d1d1f]">{{ $post->author->name }}</p>
                            @if($post->author->bio)
                                <div class="mt-2 text-sm font-light leading-relaxed text-[#52525b] prose prose-sm max-w-none prose-p:text-[#52525b] prose-p:font-light">{!! $post->author->bio !!}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Transform tables to mobile cards --}}
    @script
    <script>
        document.querySelectorAll('.prose table').forEach(table => {
            const headers = [...table.querySelectorAll('thead th')].map(th => th.textContent.replace(/\*/g, '').trim());
            table.querySelectorAll('tbody tr').forEach(tr => {
                tr.querySelectorAll('td').forEach((td, i) => {
                    if (headers[i]) {
                        td.setAttribute('data-label', headers[i]);
                    }
                });
            });
        });
    </script>
    @endscript

    {{-- Related posts --}}
    @if($relatedPosts->isNotEmpty())
        <section class="bg-white px-5 py-16 sm:px-8 sm:py-20 lg:px-10">
            <div class="mx-auto max-w-7xl">
                <div class="mb-10 flex items-end justify-between">
                    <h2 class="text-2xl font-bold tracking-tight text-[#1d1d1f]">
                        {{ __('Related articles') }}
                    </h2>
                    <a href="{{ route('marketing-suite.blog') }}" wire:navigate class="group hidden items-center gap-1.5 text-sm font-semibold text-[#1565C0] transition-colors hover:text-brand sm:flex">
                        {{ __('All articles') }}
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($relatedPosts as $related)
                        <x-blog-card :post="$related" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
