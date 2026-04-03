<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class HeroSection extends Component
{
    public ?string $backgroundImage = '';

    public ?string $badge = '';

    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{label: string, url: string, style?: string}> */
    public array $buttons = [];

    /** @var array<int, array{value: string, label: string}> */
    public array $statistics = [];

    public function mount(
        ?string $backgroundImage = '',
        ?string $badge = '',
        ?string $title = '',
        ?string $subtitle = '',
        array $buttons = [],
        array $statistics = [],
    ): void {
        $this->backgroundImage = $backgroundImage ?? '';
        $this->badge = $badge ?? '';
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->buttons = $buttons;
        $this->statistics = $statistics;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.hero-section');
    }
}
