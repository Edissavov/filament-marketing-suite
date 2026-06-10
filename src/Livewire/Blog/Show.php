<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\Blog;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use VasilGerginski\MarketingSuite\Models\BlogPost;

#[Layout('marketing-suite::components.layouts.public')]
class Show extends Component
{
    public BlogPost $post;

    public function mount(BlogPost $post): void
    {
        if (! $post->is_published) {
            abort(404);
        }

        $this->post = $post->loadMissing('author');
    }

    #[Computed]
    public function readingTime(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->post->content)) / 200));
    }

    public function render(): View
    {
        return view('marketing-suite::livewire.blog.show', [
            'readingTime' => $this->readingTime(),
            'relatedPosts' => BlogPost::query()
                ->where('is_published', true)
                ->where('id', '!=', $this->post->id)
                ->with('author')
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ])
            ->title(($this->post->meta_title ?: $this->post->title) . ' — ' . config('app.name', 'Find2Be'))
            ->layoutData([
                'seoDescription' => $this->post->meta_description ?: str($this->post->content)->stripTags()->limit(160)->toString(),
                'seoImage' => $this->post->image_url,
            ]);
    }
}
