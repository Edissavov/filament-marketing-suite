<section id="newsletter" class="bg-[#1565C0]/5 py-14 sm:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl border border-[#1565C0]/10 bg-white px-8 py-10 shadow-sm sm:px-12">
            <div class="mx-auto max-w-2xl text-center">

                {{-- Header --}}
                @if($title)
                    <h2 class="text-2xl font-bold tracking-tight text-[#003879] sm:text-3xl">
                        {{ $title }}
                    </h2>
                @endif

                @if($subtitle)
                    <p class="mt-3 text-base text-gray-600">
                        {{ $subtitle }}
                    </p>
                @endif

                {{-- Form / Success --}}
                <div class="mt-8">
                    @if($submitted)
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-base font-semibold text-[#003879]">{{ $successMessage }}</p>
                        </div>
                    @else
                        <form wire:submit="subscribe" class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                            <div class="flex-1 sm:max-w-sm">
                                <input
                                    type="email"
                                    wire:model="email"
                                    placeholder="{{ __('Your email address') }}"
                                    class="block w-full rounded-full border border-gray-200 px-5 py-3 text-sm text-gray-900 placeholder-gray-400 transition focus:border-[#1565C0] focus:outline-none focus:ring-2 focus:ring-[#1565C0]/20"
                                >
                                @error('email')
                                    <p class="mt-1.5 text-left text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-75"
                                class="shrink-0 rounded-full bg-[#1565C0] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1565C0]/90 focus:outline-none focus:ring-2 focus:ring-[#1565C0] focus:ring-offset-2 disabled:opacity-75"
                            >
                                <span wire:loading.remove>{{ $buttonText }}</span>
                                <span wire:loading>{{ __('Subscribing...') }}</span>
                            </button>
                        </form>

                        {{-- Privacy note --}}
                        @if($privacyText)
                            <p class="mt-4 text-xs text-gray-400">
                                {{ $privacyText }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
