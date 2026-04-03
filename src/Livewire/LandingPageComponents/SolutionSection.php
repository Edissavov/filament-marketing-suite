<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class SolutionSection extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{number: int|string, title: string, description: string}> */
    public array $steps = [];

    /** @var array<int, string> */
    public array $benefits = [];

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $steps = [],
        array $benefits = [],
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->steps = $steps;
        $this->benefits = $benefits;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.solution-section');
    }
}
