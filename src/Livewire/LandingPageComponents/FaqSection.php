<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class FaqSection extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{question: string, answer: string}> */
    public array $questions = [];

    public ?string $ctaText = '';

    public ?string $ctaLink = '';

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $questions = [],
        ?string $ctaText = '',
        ?string $ctaLink = '',
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->questions = $questions;
        $this->ctaText = $ctaText ?? '';
        $this->ctaLink = $ctaLink ?? '';
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.faq-section');
    }
}
