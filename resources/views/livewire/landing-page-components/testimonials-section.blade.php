<div class="py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if ($title || $subtitle)
            <div class="mx-auto max-w-2xl text-center">
                @if ($title)
                    <h2 class="text-3xl font-bold tracking-tight text-[#003879] sm:text-4xl">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="mt-4 text-lg text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        @if ($testimonials)
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <div wire:key="testimonial-{{ $loop->index }}" class="flex flex-col rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                        {{-- Quote icon --}}
                        <svg class="mb-4 h-8 w-8 text-[#1565C0]/20" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                        </svg>

                        {{-- Testimonial content --}}
                        <p class="flex-1 text-base leading-relaxed text-gray-700">{{ $testimonial['content'] }}</p>

                        {{-- Star rating --}}
                        @if (isset($testimonial['rating']) && $testimonial['rating'] > 0)
                            <div class="mt-4 flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg
                                        class="h-4 w-4 {{ $i <= $testimonial['rating'] ? 'text-[#FFB60F]' : 'text-gray-200' }}"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        @endif

                        {{-- Author --}}
                        <div class="mt-4 flex items-center gap-3 border-t border-gray-100 pt-4">
                            @if (isset($testimonial['avatar']) && $testimonial['avatar'])
                                <img
                                    src="{{ $testimonial['avatar'] }}"
                                    alt="{{ $testimonial['name'] }}"
                                    class="h-10 w-10 rounded-full object-cover"
                                >
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1565C0]/10 text-sm font-semibold text-[#1565C0]">
                                    {{ mb_strtoupper(mb_substr($testimonial['name'], 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $testimonial['name'] }}</p>
                                @if (isset($testimonial['role']) && $testimonial['role'])
                                    <p class="text-xs text-gray-500">{{ $testimonial['role'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
