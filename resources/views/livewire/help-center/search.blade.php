<div class="min-h-[calc(100vh-64px)]">
    {{-- Breadcrumbs --}}
    <div class="bg-white border-b">
        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('marketing-suite.help') }}" class="hover:text-[#1565C0]">{{ __('Help Center') }}</a>
                <span>/</span>
                <span class="font-medium text-[#1565C0]">{{ __('Search') }}</span>
            </nav>
        </div>
    </div>

    {{-- Search bar --}}
    <div class="bg-linear-to-br from-[#1565C0] to-blue-900 px-4 py-12 text-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="text-2xl font-bold sm:text-3xl">{{ __('Search results') }}</h1>
            <div class="relative mt-6">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search articles...') }}"
                    class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white placeholder-blue-200 backdrop-blur-sm ring-1 ring-white/20 focus:bg-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                >
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                    <x-tabler-search class="h-5 w-5 text-blue-200" wire:loading.remove wire:target="search" />
                    <x-tabler-loader-2 class="h-5 w-5 animate-spin text-blue-200" wire:loading wire:target="search" />
                </div>
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="bg-[#F5F5F5] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto flex max-w-7xl gap-8">
            {{-- Sidebar --}}
            <aside class="hidden w-64 shrink-0 lg:block">
                <nav class="sticky top-20 space-y-1">
                    @foreach($categories as $cat)
                        <a href="{{ route('marketing-suite.help.category', $cat) }}"
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-600 transition hover:bg-white hover:text-gray-900">
                            @if($cat->icon)
                                <x-dynamic-component :component="$cat->icon" class="h-4 w-4 shrink-0" />
                            @endif
                            {{ __($cat->name) }}
                        </a>
                    @endforeach
                </nav>
            </aside>

            {{-- Main content --}}
            <div class="min-w-0 flex-1">
                @if(mb_strlen(trim($search)) < 2)
                    <div class="rounded-xl bg-white p-8 text-center shadow-sm">
                        <x-tabler-search class="mx-auto h-12 w-12 text-gray-300" />
                        <p class="mt-3 text-gray-500">{{ __('Enter at least 2 characters to search.') }}</p>
                    </div>
                @elseif($results->isEmpty())
                    <div class="mb-6">
                        <p class="text-sm text-gray-500">
                            {{ trans_choice('{0} No results found|{1} 1 result found|[2,*] :count results found', 0) }}
                            {{ __('for') }} "<span class="font-medium text-gray-700">{{ $search }}</span>"
                        </p>
                    </div>
                    <div class="rounded-xl bg-white p-8 text-center shadow-sm">
                        <x-tabler-search-off class="mx-auto h-12 w-12 text-gray-300" />
                        <p class="mt-3 text-gray-500">{{ __('Try a different search term.') }}</p>
                    </div>
                @else
                    <div class="mb-6">
                        <p class="text-sm text-gray-500">
                            {{ trans_choice('{0} No results found|{1} 1 result found|[2,*] :count results found', $results->count(), ['count' => $results->count()]) }}
                            {{ __('for') }} "<span class="font-medium text-gray-700">{{ $search }}</span>"
                        </p>
                    </div>
                    <div class="space-y-3">
                        @foreach($results as $result)
                            <a href="{{ route('marketing-suite.help.article', [$result->category, $result]) }}"
                                wire:key="search-{{ $result->id }}"
                                wire:navigate
                                class="block rounded-xl bg-white p-6 shadow-sm transition hover:shadow-md">
                                <h3 class="font-semibold text-gray-900">{!! $this->highlightTerm(__($result->title)) !!}</h3>
                                <p class="mt-1 text-sm text-[#1565C0]">{!! $this->highlightTerm(__($result->category->name)) !!}</p>
                                <p class="mt-2 line-clamp-2 text-sm text-gray-500">{!! $this->highlightTerm(Str::limit(strip_tags($result->content), 150)) !!}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
