<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\Pages;

use VasilGerginski\MarketingSuite\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class Blog extends Component
{
    use WithPagination;

    #[Url]
    public string $category = '';

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function render(): View
    {
        $posts = BlogPost::query()
            ->where('is_published', true)
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->with('author')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('marketing-suite::livewire.pages.blog', [
            'posts' => $posts,
            'categories' => ['financial', 'other', 'global', 'authorial'],
        ])
            ->title(__('Blog').' — '.config('app.name', 'Find2Be'))
            ->layoutData([
                'seoDescription' => __('Stay informed about all developments in the Find2Be world.'),
            ]);
    }
}
