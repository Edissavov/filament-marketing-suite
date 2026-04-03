<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class CtaSection extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    public ?string $buttonText = '';

    public ?string $buttonLink = '';

    /** @var array<int, string> */
    public array $features = [];

    /** @var array{content: string, name: string, role: string} */
    public array $testimonial = [];

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        ?string $buttonText = '',
        ?string $buttonLink = '',
        array $features = [],
        array $testimonial = [],
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->buttonText = $buttonText ?? '';
        $this->buttonLink = $buttonLink ?? '';
        $this->features = $features;
        $this->testimonial = $testimonial;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.cta-section');
    }
}
