<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\LandingPageComponents;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class IconListSection extends Component
{
    public ?string $title = '';

    public ?string $subtitle = '';

    /** @var array<int, array{icon: string, title: string, description: string}> */
    public array $items = [];

    /**
     * @param  array<int, array{icon: string, title: string, description: string}>  $items
     */
    public function mount(
        ?string $title = '',
        ?string $subtitle = '',
        array $items = [],
    ): void {
        $this->title = $title ?? '';
        $this->subtitle = $subtitle ?? '';
        $this->items = $items;
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.landing-page-components.icon-list-section');
    }
}
