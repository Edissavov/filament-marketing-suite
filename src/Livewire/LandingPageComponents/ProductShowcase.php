<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProductShowcase extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{name: string, description: string, features: array<int, string>, link: string, image: string}> */
    public array $products = [];

    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $products = [],
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->products = $products;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.product-showcase');
    }
}
