@props([
    'post',
    'variant' => 'default',
    'index' => 0,
    'animated' => false,
])

<article {{ $attributes->class([
    'group relative flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-shadow duration-200 hover:shadow-md',
    'sm:flex-row sm:items-center' => $variant === 'compact',
]) }}>
    @if ($post->image)
        <a href="{{ route('marketing-suite.blog.show', $post) }}" wire:navigate
            class="{{ $variant === 'compact' ? 'block w-full shrink-0 sm:w-40' : 'block aspect-video' }} overflow-hidden">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
        </a>
    @endif

    <div class="flex flex-1 flex-col gap-2 p-5">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            @if ($post->category)
                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 font-medium text-[#1565C0]">
                    {{ __(ucfirst($post->category)) }}
                </span>
            @endif
            @if ($post->published_at)
                <time datetime="{{ $post->published_at->toDateString() }}">
                    {{ $post->published_at->translatedFormat('d M Y') }}
                </time>
            @endif
        </div>

        <h3 class="{{ $variant === 'compact' ? 'text-base' : 'text-lg' }} font-semibold leading-snug text-gray-900">
            <a href="{{ route('marketing-suite.blog.show', $post) }}" wire:navigate
                class="transition-colors hover:text-[#1565C0]">
                {{ $post->title }}<span class="absolute inset-0"></span>
            </a>
        </h3>

        @if ($variant !== 'compact')
            <p class="line-clamp-2 text-sm text-gray-600">
                {{ str($post->content)->stripTags()->limit(140) }}
            </p>
        @endif

        @if ($post->author)
            <span class="mt-auto pt-2 text-xs font-medium text-gray-500">{{ $post->author->name }}</span>
        @endif
    </div>
</article>
