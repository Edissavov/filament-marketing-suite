<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\Blog;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use VasilGerginski\MarketingSuite\Models\BlogPost;

#[Layout('marketing-suite::components.layouts.public')]
class Index extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('marketing-suite::livewire.blog.index', [
            'posts' => BlogPost::query()
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(9),
        ]);
    }
}
