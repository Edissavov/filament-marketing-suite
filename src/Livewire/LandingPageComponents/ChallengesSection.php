<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ChallengesSection extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{icon: string, title: string, description: string}> */
    public array $challenges = [];

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $challenges = [],
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->challenges = $challenges;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.challenges-section');
    }
}
