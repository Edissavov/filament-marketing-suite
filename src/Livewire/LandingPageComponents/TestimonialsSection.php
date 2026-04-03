<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class TestimonialsSection extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{name: string, role: string, content: string, rating: int, avatar: string}> */
    public array $testimonials = [];

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $testimonials = [],
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->testimonials = $testimonials;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.testimonials-section');
    }
}
