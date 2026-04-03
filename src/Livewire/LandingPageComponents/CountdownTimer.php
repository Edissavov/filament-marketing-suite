<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class CountdownTimer extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    public ?string $targetDate = '';

    public ?string $buttonText = '';

    public ?string $buttonLink = '';

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        ?string $targetDate = '',
        ?string $buttonText = 'Регистрирайте се',
        ?string $buttonLink = '/register',
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->targetDate = $targetDate ?? '';
        $this->buttonText = $buttonText ?? 'Регистрирайте се';
        $this->buttonLink = $buttonLink ?? '/register';
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.countdown-timer');
    }
}
