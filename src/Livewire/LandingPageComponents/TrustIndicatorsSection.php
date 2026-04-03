<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class TrustIndicatorsSection extends Component
{
    public ?string $title = '';

    /** @var array<int, array{icon: string, title: string, description: string}> */
    public array $indicators = [];

    /**
     * @param  array<int, array{icon: string, title: string, description: string}>  $indicators
     */
    public function mount(
        ?string $title = '',
        array $indicators = [],
    ): void {
        $this->title = $title ?? '';
        $this->indicators = $indicators;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.trust-indicators-section');
    }
}
