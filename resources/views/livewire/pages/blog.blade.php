<div>
    {{-- HERO --}}
    <section class="bg-apple-gray px-5 pt-28 pb-0 sm:px-8 sm:pt-36 lg:px-10">
        <div class="mx-auto max-w-4xl text-center">
            <h1 class="text-3xl font-semibold tracking-[-0.02em] text-brand sm:text-4xl lg:text-5xl">
                {{ __('Blog') }}
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg font-light leading-relaxed text-apple-secondary">
                {{ __('Stay informed about all developments in the Find2Be world.') }}
            </p>
        </div>
    </section>

    {{-- POSTS GRID --}}
    <section class="bg-apple-gray px-5 py-12 sm:px-8 sm:py-16 lg:px-10">
        <div class="mx-auto max-w-7xl" x-data="{ shown: false }" x-intersect.margin.-100px="shown = true">

            {{-- Category filter --}}
            <div :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 class="transition-all duration-700 ease-apple mb-8 flex flex-wrap items-center gap-2">
                <button wire:click="setCategory('')"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold transition-colors duration-200 {{ $category === '' ? 'bg-brand text-white shadow-[0_2px_8px_rgba(0,56,121,0.25)]' : 'bg-white text-apple-secondary border border-[#e0e0e0] hover:border-[#c8c8c8] hover:text-[#1d1d1f]' }}">
                    {{ __('All Posts') }}
                </button>
                @foreach($categories as $cat)
                    <button wire:click="setCategory('{{ $cat }}')"
                            class="rounded-full px-5 py-2.5 text-sm font-semibold transition-colors duration-200 {{ $category === $cat ? 'bg-brand text-white shadow-[0_2px_8px_rgba(0,56,121,0.25)]' : 'bg-white text-apple-secondary border border-[#e0e0e0] hover:border-[#c8c8c8] hover:text-[#1d1d1f]' }}">
                        {{ __(ucfirst($cat)) }}
                    </button>
                @endforeach

                <div wire:loading class="ml-1">
                    <svg class="h-5 w-5 animate-spin text-brand/50" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </div>
            </div>

            {{-- Posts --}}
            @if($posts->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class="opacity-60" wire:target="setCategory">
                    @foreach($posts as $index => $post)
                        <x-blog-card :post="$post" :index="$index" animated />
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-[#c8c8c8] bg-white p-16 text-center shadow-sm sm:p-20">
                    <svg class="mx-auto mb-4 h-10 w-10 text-[#c8c8c8]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                    <p class="text-base font-light text-apple-secondary">{{ __('No blog posts yet.') }}</p>
                    @if($category)
                        <button wire:click="setCategory('')"
                                class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition-all duration-200 hover:gap-2.5">
                            {{ __('View all posts') }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif
                </div>
            @endif

            @if($posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
