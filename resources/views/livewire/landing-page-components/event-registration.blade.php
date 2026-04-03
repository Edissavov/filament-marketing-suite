@php
    use Carbon\Carbon;
@endphp

<section id="register" class="bg-[#F5F5F5] py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        @if($title || $subtitle)
            <div class="mx-auto mb-12 max-w-2xl text-center">
                @if($title)
                    <h2 class="text-3xl font-bold tracking-tight text-brand sm:text-4xl">
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

        <div class="grid gap-8 lg:grid-cols-2">

            {{-- Event info card --}}
            <div class="rounded-2xl border border-[#1565C0]/10 bg-[#1565C0] p-8 text-white shadow-xl relative overflow-hidden">
                <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5"></div>
                <div class="pointer-events-none absolute -left-16 -bottom-16 h-48 w-48 rounded-full bg-black/5"></div>

                <div class="relative z-10">
                    <h3 class="text-2xl font-bold text-white">
                        {{ $title }}
                    </h3>

                    @if($subtitle)
                        <p class="mt-3 text-blue-100 leading-relaxed">{{ $subtitle }}</p>
                    @endif

                    <div class="mt-10 space-y-6">
                        {{-- Date --}}
                        @if($eventDate)
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Date</p>
                                    <p class="mt-1 text-lg font-semibold text-white">{{ $eventDate }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Time --}}
                        @if($eventTime)
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Time</p>
                                    <p class="mt-1 text-lg font-semibold text-white">{{ $eventTime }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Location --}}
                        @if($eventLocation)
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Location</p>
                                    <p class="mt-1 text-lg font-semibold text-white">{{ $eventLocation }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Decorative footer --}}
                    <div class="mt-10 border-t border-white/10 pt-8">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                @for($i = 1; $i <= 3; $i++)
                                    <div class="h-8 w-8 rounded-full border-2 border-[#1565C0] bg-blue-400"></div>
                                @endfor
                            </div>
                            <p class="text-sm text-blue-100 italic">
                                {{ __('Join dozens of others who have already registered.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Registration form card --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">

                @if($submitted)
                    {{-- Success state --}}
                    <div class="flex h-full flex-col items-center justify-center py-12 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-50">
                            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-2xl font-bold text-brand">Registration Complete</h3>
                        <p class="mt-3 text-lg text-gray-600">{{ $successMessage }}</p>
                        <button
                            wire:click="$set('submitted', false)"
                            class="mt-10 rounded-full bg-[#1565C0] px-8 py-3 text-sm font-semibold text-white transition hover:bg-[#1565C0]/90"
                        >
                            Register another person
                        </button>
                    </div>
                @else
                    {{-- Form --}}
                    <h3 class="text-xl font-bold text-brand">{{ __('Secure Your Spot') }}</h3>
                    <p class="mt-1 text-gray-500">{{ __('Fill in your details below to book your consultation.') }}</p>

                    <form wire:submit="register" class="mt-8 space-y-6">
                        {{-- Form Fields --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            @foreach($fields as $field)
                                <div class="{{ ($field['type'] ?? '') === 'textarea' ? 'sm:col-span-2' : '' }}">
                                    <label
                                        for="field-{{ $field['name'] ?? $loop->index }}"
                                        class="block text-sm font-semibold text-gray-700"
                                    >
                                        {{ $field['label'] ?? '' }}
                                        @if($field['required'] ?? false)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if(($field['type'] ?? '') === 'textarea')
                                        <textarea
                                            id="field-{{ $field['name'] ?? $loop->index }}"
                                            wire:model="formData.{{ $field['name'] ?? '' }}"
                                            rows="3"
                                            @if($field['required'] ?? false) required @endif
                                            placeholder="Tell us a bit about what you're looking for..."
                                            class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition focus:border-[#1565C0] focus:outline-none focus:ring-4 focus:ring-[#1565C0]/10"
                                        ></textarea>
                                    @elseif(($field['type'] ?? '') === 'select')
                                        <select
                                            id="field-{{ $field['name'] ?? $loop->index }}"
                                            wire:model="formData.{{ $field['name'] ?? '' }}"
                                            @if($field['required'] ?? false) required @endif
                                            class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 transition focus:border-[#1565C0] focus:outline-none focus:ring-4 focus:ring-[#1565C0]/10"
                                        >
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach($field['options'] ?? [] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input
                                            type="{{ $field['type'] ?? 'text' }}"
                                            id="field-{{ $field['name'] ?? $loop->index }}"
                                            wire:model="formData.{{ $field['name'] ?? '' }}"
                                            @if($field['required'] ?? false) required @endif
                                            class="mt-1.5 block w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition focus:border-[#1565C0] focus:outline-none focus:ring-4 focus:ring-[#1565C0]/10"
                                        >
                                    @endif

                                    @if(isset($field['name']))
                                        @error('formData.'.$field['name'])
                                            <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
                                        @enderror
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75"
                            class="group relative w-full overflow-hidden rounded-full bg-[#1565C0] px-8 py-4 text-lg font-bold text-white shadow-lg transition-all hover:bg-[#1565C0]/90 active:scale-[0.98] disabled:opacity-75"
                        >
                            <span wire:loading.remove>{{ $buttonText }}</span>
                            <span wire:loading class="flex items-center justify-center gap-2">
                                <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('Booking...') }}
                            </span>
                        </button>

                        <p class="text-center text-xs text-gray-400">
                            {{ __('By clicking ":button", you agree to our Terms of Service and Privacy Policy.', ['button' => $buttonText]) }}
                        </p>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
