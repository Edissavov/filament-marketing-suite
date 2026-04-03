<div class="py-16 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        @if ($title || $subtitle)
            <div class="text-center">
                @if ($title)
                    <h2 class="text-3xl font-bold tracking-tight text-[#003879] sm:text-4xl">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="mt-4 text-lg text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        @if ($questions)
            <div class="mt-12 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100" x-data="{ active: null }">
                @foreach ($questions as $item)
                    <div wire:key="faq-{{ $loop->index }}" class="border-t border-gray-100 first:border-t-0">
                        <button
                            type="button"
                            @click="active = active === {{ $loop->index }} ? null : {{ $loop->index }}"
                            :aria-expanded="active === {{ $loop->index }}"
                            aria-controls="faq-answer-{{ $loop->index }}"
                            class="flex w-full items-center gap-3 px-6 py-5 text-left transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1565C0] focus-visible:ring-inset"
                            :class="active === {{ $loop->index }} ? 'bg-[#f8faff]' : 'hover:bg-gray-50'"
                        >
                            <svg class="h-[18px] w-[18px] shrink-0 text-[#1565C0] opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 0 1-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 0 1-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 0 1-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584ZM12 18a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" /></svg>
                            <span class="flex-1 text-[0.9375rem] font-semibold text-[#1d1d1f]">{{ $item['question'] }}</span>
                            <svg class="h-4 w-4 shrink-0 transition-all duration-300"
                                 :class="active === {{ $loop->index }} ? 'rotate-[135deg] text-[#1565C0] opacity-100' : 'text-gray-400 opacity-50'"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" /></svg>
                        </button>
                        <div id="faq-answer-{{ $loop->index }}"
                             role="region"
                             x-show="active === {{ $loop->index }}"
                             x-collapse
                             x-cloak>
                            <div class="px-6 pb-5 pl-[3.125rem] text-sm leading-relaxed text-gray-600">
                                <p>{{ $item['answer'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($ctaText && $ctaLink)
            <div class="mt-10 text-center">
                <p class="text-sm text-gray-500">
                    {{ __('Still have questions?') }}
                    <a href="{{ $ctaLink }}" class="font-semibold text-[#1565C0] hover:text-[#003879] hover:underline">{{ $ctaText }}</a>
                </p>
            </div>
        @endif
    </div>
</div>
