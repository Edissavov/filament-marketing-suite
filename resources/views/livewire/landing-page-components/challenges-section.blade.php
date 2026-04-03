<section class="bg-[#F5F5F5] py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        @if($title || $subtitle)
            <div class="mx-auto mb-12 max-w-2xl text-center">
                @if($title)
                    <h2 class="text-3xl font-bold tracking-tight text-[#003879] sm:text-4xl">
                        {{ $title }}
                    </h2>
                @endif
                @if($subtitle)
                    <p class="mt-4 text-lg text-gray-600">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Challenge cards --}}
        @if(count($challenges) > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($challenges as $challenge)
                    <div class="group rounded-xl border border-red-100 bg-white p-6 shadow-sm transition hover:shadow-md">
                        {{-- Icon --}}
                        @if(!empty($challenge['icon']))
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500 transition group-hover:bg-red-100">
                                <span class="text-2xl leading-none">{!! $challenge['icon'] !!}</span>
                            </div>
                        @endif

                        {{-- Title --}}
                        @if(!empty($challenge['title']))
                            <h3 class="mb-2 text-lg font-semibold text-[#003879]">
                                {{ $challenge['title'] }}
                            </h3>
                        @endif

                        {{-- Description --}}
                        @if(!empty($challenge['description']))
                            <p class="text-sm leading-relaxed text-gray-600">
                                {{ $challenge['description'] }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
