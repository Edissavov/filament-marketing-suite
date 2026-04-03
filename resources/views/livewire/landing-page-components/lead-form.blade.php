<div id="lead-form" class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
        @if ($submitted)
            <div class="py-8 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">
                    {{ $successMessage ?: __('Thank you!') }}
                </h3>
                <button
                    wire:click="$set('submitted', false)"
                    class="mt-6 text-sm font-semibold text-[#1565C0] hover:text-[#003879] hover:underline"
                >
                    {{ __('Submit another response') }}
                </button>
            </div>
        @else
            @if ($title || $subtitle)
                <div class="mb-6">
                    @if ($title)
                        <h3 class="text-xl font-bold text-[#003879]">{{ $title }}</h3>
                    @endif
                    @if ($subtitle)
                        <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            <form wire:submit="submitForm" class="space-y-5">
                @foreach ($fields as $field)
                    <div wire:key="field-{{ $field['name'] ?? $loop->index }}">
                        <label
                            for="lead-{{ $field['name'] ?? $loop->index }}"
                            class="block text-sm font-medium text-gray-700"
                        >
                            {{ $field['label'] ?? '' }}
                            @if ($field['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea
                                id="lead-{{ $field['name'] ?? $loop->index }}"
                                wire:model.blur="formData.{{ $field['name'] ?? '' }}"
                                rows="4"
                                class="mt-1 block w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-[#1565C0] focus:outline-none focus:ring-1 focus:ring-[#1565C0]"
                                @if ($field['required'] ?? false) required @endif
                            ></textarea>
                        @else
                            <input
                                type="{{ $field['type'] ?? 'text' }}"
                                id="lead-{{ $field['name'] ?? $loop->index }}"
                                wire:model.blur="formData.{{ $field['name'] ?? '' }}"
                                class="mt-1 block w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-[#1565C0] focus:outline-none focus:ring-1 focus:ring-[#1565C0]"
                                @if ($field['required'] ?? false) required @endif
                            >
                        @endif

                        @if(isset($field['name']))
                            @error('formData.' . $field['name'])
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                @endforeach

                <button
                    type="submit"
                    class="w-full rounded-full bg-[#1565C0] px-8 py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-[#003879] focus:outline-none focus:ring-2 focus:ring-[#1565C0] focus:ring-offset-2"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75"
                >
                    <span wire:loading.remove>{{ $buttonText ?: __('Submit') }}</span>
                    <span wire:loading>{{ __('Submitting...') }}</span>
                </button>
            </form>
        @endif
    </div>
</div>
