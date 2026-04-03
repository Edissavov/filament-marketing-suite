<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\Pages;

use VasilGerginski\MarketingSuite\Models\BlogPost;
use VasilGerginski\MarketingSuite\Models\Definition;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Definicii extends Component
{
    public function render(): View
    {
        return view('marketing-suite::livewire.pages.definicii', [
            'definitions' => Definition::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'relatedPosts' => BlogPost::query()->where('is_published', true)->orderByDesc('published_at')->limit(3)->get(),
        ]);
    }
}
