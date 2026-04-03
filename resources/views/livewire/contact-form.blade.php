<div>
    @if ($submitted)
        <div class="rounded-2xl bg-white/20 p-8 text-center backdrop-blur-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="mt-4 text-xl font-semibold text-white">{{ __('Message sent!') }}</h3>
            <p class="mt-2 text-blue-100">{{ __('We will get back to you as soon as possible.') }}</p>
            <button
                wire:click="$set('submitted', false)"
                class="mt-6 rounded-full bg-white px-6 py-2 text-sm font-medium text-brand-light transition hover:bg-gray-100"
            >
                {{ __('Send a new inquiry') }}
            </button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="contact-name" class="block text-sm font-medium text-white">{{ __('Name') }}</label>
                    <input
                        type="text"
                        id="contact-name"
                        wire:model.blur="name"
                        placeholder="{{ __('Your name') }}"
                        class="mt-1 block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder-blue-200 backdrop-blur-sm focus:bg-white/20 focus:ring-2 focus:ring-white/50"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="contact-email" class="block text-sm font-medium text-white">{{ __('Email') }}</label>
                    <input
                        type="email"
                        id="contact-email"
                        wire:model.blur="email"
                        placeholder="{{ __('your@email.com') }}"
                        class="mt-1 block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder-blue-200 backdrop-blur-sm focus:bg-white/20 focus:ring-2 focus:ring-white/50"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="contact-category" class="block text-sm font-medium text-white">{{ __('Category') }}</label>
                <select
                    id="contact-category"
                    wire:model.blur="category"
                    class="mt-1 block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white backdrop-blur-sm focus:bg-white/20 focus:ring-2 focus:ring-white/50"
                >
                    <option value="" class="text-gray-900">{{ __('Select a category') }}</option>
                    <option value="registration" class="text-gray-900">{{ __('Registration') }}</option>
                    <option value="verification" class="text-gray-900">{{ __('Verification') }}</option>
                    <option value="deposit" class="text-gray-900">{{ __('Deposit') }}</option>
                    <option value="withdrawal" class="text-gray-900">{{ __('Withdrawal') }}</option>
                    <option value="investing" class="text-gray-900">{{ __('Investing') }}</option>
                    <option value="other" class="text-gray-900">{{ __('Other') }}</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-message" class="block text-sm font-medium text-white">{{ __('Your message') }}</label>
                <textarea
                    id="contact-message"
                    wire:model.blur="message"
                    rows="4"
                    placeholder="{{ __('Describe your inquiry...') }}"
                    class="mt-1 block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder-blue-200 backdrop-blur-sm focus:bg-white/20 focus:ring-2 focus:ring-white/50"
                ></textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded-full bg-white px-8 py-3.5 text-base font-semibold text-brand-light shadow-lg transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-brand-light sm:w-auto"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75"
            >
                <span wire:loading.remove>{{ __('Send') }}</span>
                <span wire:loading>{{ __('Sending...') }}</span>
            </button>
        </form>
    @endif
</div>
