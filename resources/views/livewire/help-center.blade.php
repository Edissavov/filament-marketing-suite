<div class="min-h-[calc(100vh-64px)]">
    {{-- Breadcrumbs --}}
    <div class="bg-white border-b">
        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('help-center') }}" class="hover:text-[#1565C0] {{ !$category ? 'font-medium text-[#1565C0]' : '' }}">{{ __('Help Center') }}</a>
                @if($category)
                    <span>/</span>
                    <a href="{{ route('help-center.category', $category) }}" class="hover:text-[#1565C0] {{ !$article ? 'font-medium text-[#1565C0]' : '' }}">{{ __($category->name) }}</a>
                @endif
                @if($article)
                    <span>/</span>
                    <span class="font-medium text-[#1565C0]">{{ __($article->title) }}</span>
                @endif
            </nav>
        </div>
    </div>

    @if(!$category && !$article)
        {{-- INDEX VIEW: Search + Category Grid --}}
        <div class="bg-linear-to-br from-[#1565C0] to-blue-900 px-4 py-16 text-white sm:px-6 lg:px-8"
             x-data="{ shown: false }" x-init="shown = false; $nextTick(() => setTimeout(() => shown = true, 80))">
            <div class="mx-auto max-w-3xl text-center">
                <h1 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    class="transition-all duration-700 delay-100 ease-apple text-3xl font-bold sm:text-4xl">{{ __('How can we help you?') }}</h1>
                <p :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                   class="transition-all duration-700 delay-200 ease-apple mt-3 text-blue-100">{{ __('Find answers to your questions about the platform') }}</p>
                <form action="{{ route('help-center.search') }}" method="GET" class="relative mt-8">
                    <input
                        type="text"
                        name="q"
                        placeholder="{{ __('Search articles...') }}"
                        class="w-full rounded-xl border-0 bg-white/10 px-5 py-3.5 text-white placeholder-blue-200 backdrop-blur-sm ring-1 ring-white/20 focus:bg-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <x-tabler-search class="h-5 w-5 text-blue-200" />
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-[#F5F5F5] px-4 py-12 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl" x-data="{ shown: false }" x-intersect.margin.-50px.once="shown = true">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($categories as $cat)
                        <a href="{{ route('help-center.category', $cat) }}"
                            wire:key="cat-{{ $cat->id }}"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                            class="transition-all duration-700 ease-apple group rounded-xl bg-white p-6 shadow-sm hover:shadow-md hover:-translate-y-1"
                            style="transition-delay: {{ $loop->index * 80 }}ms">
                            @if($cat->icon)
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-[#1565C0] transition group-hover:bg-[#1565C0] group-hover:text-white">
                                    <x-dynamic-component :component="$cat->icon" class="h-6 w-6" />
                                </div>
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-[#1565C0] transition group-hover:bg-[#1565C0] group-hover:text-white">
                                    <x-tabler-help-circle class="h-6 w-6" />
                                </div>
                            @endif
                            <h3 class="mt-4 font-semibold text-gray-900">{{ __($cat->name) }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $cat->articles_count }} {{ trans_choice('{1} article|[2,*] articles', $cat->articles_count) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif($category && !$article)
        {{-- CATEGORY VIEW: Articles list (accordion style) --}}
        <div class="bg-[#F5F5F5] px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl gap-8">
                {{-- Sidebar --}}
                <aside class="hidden w-64 shrink-0 lg:block">
                    <nav class="sticky top-20 space-y-1">
                        @foreach($categories as $cat)
                            <a href="{{ route('help-center.category', $cat) }}"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition {{ $cat->id === $category->id ? 'bg-[#1565C0] text-white font-medium' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
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
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ __($category->name) }}</h1>
                        <p class="mt-1 text-sm text-gray-500">{{ $articles->count() }} {{ trans_choice('{1} article|[2,*] articles', $articles->count()) }}</p>
                    </div>

                    @if($articles->isEmpty())
                        <div class="rounded-xl bg-white p-8 text-center shadow-sm">
                            <x-tabler-file-text class="mx-auto h-12 w-12 text-gray-300" />
                            <p class="mt-3 text-gray-500">{{ __('No articles in this category yet.') }}</p>
                        </div>
                    @else
                        <div class="space-y-3" x-data="{ open: null }">
                            @foreach($articles as $art)
                                <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                                    <button
                                        @click="open = open === {{ $art->id }} ? null : {{ $art->id }}"
                                        class="flex w-full items-center justify-between px-6 py-4 text-left transition hover:bg-gray-50"
                                    >
                                        <span class="font-medium text-gray-900">{{ __($art->title) }}</span>
                                        <x-tabler-chevron-down class="h-5 w-5 shrink-0 text-gray-400 transition" ::class="open === {{ $art->id }} ? 'rotate-180' : ''" />
                                    </button>
                                    <div x-show="open === {{ $art->id }}" x-collapse x-cloak>
                                        <div class="border-t px-6 py-4">
                                            <div class="prose prose-sm max-w-none text-gray-600">
                                                {!! $art->content !!}
                                            </div>
                                            <div class="mt-4">
                                                <a href="{{ route('help-center.article', [$category, $art]) }}" class="text-sm font-medium text-[#1565C0] hover:underline">
                                                    {{ __('Read full article') }} &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        {{-- ARTICLE VIEW: Full article content with sidebar --}}
        <div class="bg-[#F5F5F5] px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl gap-8">
                {{-- Sidebar --}}
                <aside class="hidden w-64 shrink-0 lg:block">
                    <nav class="sticky top-20 space-y-1">
                        @foreach($categories as $cat)
                            <a href="{{ route('help-center.category', $cat) }}"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition {{ $category && $cat->id === $category->id ? 'bg-[#1565C0] text-white font-medium' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
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
                    <article class="rounded-xl bg-white shadow-sm">
                        {{-- Next / Previous navigation --}}
                        @if($category)
                            <div class="flex items-center justify-between border-b px-8 py-3">
                                <div>
                                    @if($previousArticle)
                                        <a href="{{ route('help-center.article', [$category, $previousArticle]) }}" class="inline-flex items-center gap-1 text-sm font-medium text-[#1565C0] hover:underline">
                                            <x-tabler-arrow-left class="h-4 w-4" />
                                            {{ __($previousArticle->title) }}
                                        </a>
                                    @else
                                        <a href="{{ route('help-center.category', $category) }}" class="inline-flex items-center gap-1 text-sm font-medium text-[#1565C0] hover:underline">
                                            <x-tabler-arrow-left class="h-4 w-4" />
                                            {{ __('Back to :category', ['category' => __($category->name)]) }}
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    @if($nextArticle)
                                        <a href="{{ route('help-center.article', [$category, $nextArticle]) }}" class="inline-flex items-center gap-1 text-sm font-medium text-[#1565C0] hover:underline">
                                            {{ __($nextArticle->title) }}
                                            <x-tabler-arrow-right class="h-4 w-4" />
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="p-8">
                            <h1 class="text-2xl font-bold text-gray-900">{{ __($article->title) }}</h1>
                            <p class="mt-1 text-sm text-gray-400">{{ __('Last updated') }}: {{ $article->updated_at->format('d.m.Y') }}</p>
                            <div class="mt-6 prose prose-sm max-w-none text-gray-600">
                                {!! $article->content !!}
                            </div>
                        </div>
                    </article>

                    {{-- Feedback widget --}}
                    <div class="mt-6 rounded-xl bg-white p-6 shadow-sm text-center">
                        <p class="text-sm font-medium text-gray-700">{{ __('Was this article helpful?') }}</p>
                        <div class="mt-3 flex items-center justify-center gap-3">
                            <button
                                wire:click="submitFeedback(true)"
                                class="inline-flex items-center gap-1.5 rounded-lg border px-4 py-2 text-sm font-medium transition {{ $userFeedback && $userFeedback->is_helpful ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-600 hover:border-green-300 hover:bg-green-50 hover:text-green-700' }}"
                            >
                                <x-tabler-thumb-up class="h-4 w-4" />
                                {{ __('Yes') }}
                            </button>
                            <button
                                wire:click="submitFeedback(false)"
                                class="inline-flex items-center gap-1.5 rounded-lg border px-4 py-2 text-sm font-medium transition {{ $userFeedback && !$userFeedback->is_helpful ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-600 hover:border-red-300 hover:bg-red-50 hover:text-red-700' }}"
                            >
                                <x-tabler-thumb-down class="h-4 w-4" />
                                {{ __('No') }}
                            </button>
                        </div>
                        @if($feedbackStats['total'] > 0)
                            <p class="mt-3 text-xs text-gray-400">
                                {{ __(':helpful out of :total found this helpful', ['helpful' => $feedbackStats['helpful'], 'total' => $feedbackStats['total']]) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
