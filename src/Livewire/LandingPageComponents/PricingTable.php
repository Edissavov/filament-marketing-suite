<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class PricingTable extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{name: string, price: string, period: string, features: array<int, string>, buttonText: string, buttonLink: string, isPopular: bool}> */
    public array $plans = [];

    /**
     * @param  array<int, array{name: string, price: string, period: string, features: array<int, string>, buttonText: string, buttonLink: string, isPopular: bool}>  $plans
     */
    public function mount(
        ?string $title = 'Изберете план',
        ?string $subtitle = '',
        array $plans = [],
    ): void {
        $this->title = $title ?? 'Изберете план';
        $this->subtitle = $subtitle ?? '';
        $this->plans = $plans;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.pricing-table');
    }
}
