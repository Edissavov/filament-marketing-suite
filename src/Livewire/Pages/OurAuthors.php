<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use VasilGerginski\MarketingSuite\Models\Author;

#[Layout('marketing-suite::components.layouts.public')]
class OurAuthors extends Component
{
    public function render(): View
    {
        return view('marketing-suite::livewire.pages.our-authors', [
            'authors' => Author::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->with(['blogPosts' => static fn ($q) => $q->where('is_published', true)->orderByDesc('published_at')])
                ->get(),
        ]);
    }
}
