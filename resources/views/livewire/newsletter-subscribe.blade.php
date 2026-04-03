<div class="mb-16 rounded-2xl border border-white/10 bg-white/5 p-8 text-center sm:p-10">
    <h3 class="text-lg font-semibold text-white">{{ __('Subscribe to our newsletter') }}</h3>
    <p class="mt-2 text-sm font-light text-white/50">{{ __('Stay updated with the latest investment opportunities and platform news.') }}</p>

    @if(!$submitted)
        <form wire:submit="subscribe" class="mx-auto mt-6 flex max-w-md flex-col gap-3 sm:flex-row">
            <input wire:model="email" type="email" placeholder="{{ __('Your email') }}"
                   class="flex-1 rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm text-white placeholder-white/40 outline-none transition focus:border-accent focus:ring-1 focus:ring-accent">
            <button type="submit" wire:loading.attr="disabled"
                    class="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-brand transition hover:bg-accent/90 disabled:opacity-50">
                <span wire:loading.remove>{{ __('Subscribe') }}</span>
                <span wire:loading>...</span>
            </button>
        </form>
        @error('email')
            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
        @enderror
    @else
        <p class="mt-6 text-sm font-medium text-accent">{{ __('Thank you for subscribing!') }}</p>
    @endif
</div>
