<div
    class="bg-[#1565C0] py-16 sm:py-24"
    x-data="{
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        targetDate: new Date('{{ $targetDate }}'),
        expired: false,
        init() {
            this.tick();
            setInterval(() => this.tick(), 1000);
        },
        tick() {
            const now = new Date();
            const diff = this.targetDate - now;

            if (diff <= 0) {
                this.days = 0;
                this.hours = 0;
                this.minutes = 0;
                this.seconds = 0;
                this.expired = true;
                return;
            }

            this.days    = Math.floor(diff / (1000 * 60 * 60 * 24));
            this.hours   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
        },
        pad(n) {
            return String(n).padStart(2, '0');
        }
    }"
>
    <div class="mx-auto max-w-4xl px-6 text-center lg:px-8">
        @if($title)
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $title }}</h2>
        @endif

        @if($subtitle)
            <p class="mt-4 text-lg text-white/75">{{ $subtitle }}</p>
        @endif

        <div class="mt-12 flex items-center justify-center gap-4 sm:gap-8" x-show="!expired">
            <div class="flex flex-col items-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-white/15 sm:h-24 sm:w-24">
                    <span class="text-3xl font-bold tabular-nums text-white sm:text-4xl" x-text="pad(days)">00</span>
                </div>
                <span class="mt-2 text-xs font-medium uppercase tracking-widest text-white/60">{{ __('Days') }}</span>
            </div>

            <span class="mb-6 text-3xl font-light text-white/40">:</span>

            <div class="flex flex-col items-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-white/15 sm:h-24 sm:w-24">
                    <span class="text-3xl font-bold tabular-nums text-white sm:text-4xl" x-text="pad(hours)">00</span>
                </div>
                <span class="mt-2 text-xs font-medium uppercase tracking-widest text-white/60">{{ __('Hours') }}</span>
            </div>

            <span class="mb-6 text-3xl font-light text-white/40">:</span>

            <div class="flex flex-col items-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-white/15 sm:h-24 sm:w-24">
                    <span class="text-3xl font-bold tabular-nums text-white sm:text-4xl" x-text="pad(minutes)">00</span>
                </div>
                <span class="mt-2 text-xs font-medium uppercase tracking-widest text-white/60">{{ __('Minutes') }}</span>
            </div>

            <span class="mb-6 text-3xl font-light text-white/40">:</span>

            <div class="flex flex-col items-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-white/15 sm:h-24 sm:w-24">
                    <span class="text-3xl font-bold tabular-nums text-white sm:text-4xl" x-text="pad(seconds)">00</span>
                </div>
                <span class="mt-2 text-xs font-medium uppercase tracking-widest text-white/60">{{ __('Seconds') }}</span>
            </div>
        </div>

        <p class="mt-8 text-lg font-semibold text-white/80" x-show="expired">{{ __('The offer has ended.') }}</p>

        @if($buttonText && $buttonLink)
            <div class="mt-10">
                <a
                    href="{{ $buttonLink }}"
                    class="inline-block rounded-lg bg-[#FFB60F] px-8 py-4 text-base font-semibold text-[#003879] shadow-sm transition hover:bg-[#FFB60F]/90"
                >
                    {{ $buttonText }}
                </a>
            </div>
        @endif
    </div>
</div>
