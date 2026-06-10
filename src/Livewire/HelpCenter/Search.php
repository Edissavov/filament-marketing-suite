<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire\HelpCenter;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use VasilGerginski\MarketingSuite\Models\HelpArticle;
use VasilGerginski\MarketingSuite\Models\HelpCategory;

#[Layout('marketing-suite::components.layouts.guest')]
class Search extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    public function highlightTerm(string $text): string
    {
        $term = trim($this->search);

        if (mb_strlen($term) < 2) {
            return e($text);
        }

        $escaped = e($text);
        $pattern = '/(' . preg_quote(e($term), '/') . ')/iu';

        return preg_replace($pattern, '<mark class="bg-yellow-200 rounded px-0.5">$1</mark>', $escaped);
    }

    public function render(): View
    {
        $categories = HelpCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $results = collect();
        if (mb_strlen(trim($this->search)) >= 2) {
            $term = mb_strtolower(trim($this->search));

            $results = HelpArticle::query()
                ->where('is_published', true)
                ->with('category')
                ->orderBy('sort_order')
                ->get()
                ->filter(
                    static fn (HelpArticle $a) => (
                        str_contains(mb_strtolower(__($a->title)), $term)
                        || str_contains(mb_strtolower($a->title), $term)
                        || str_contains(mb_strtolower(strip_tags($a->content)), $term)
                        || str_contains(mb_strtolower(__($a->category->name)), $term)
                        || str_contains(mb_strtolower($a->category->name), $term)
                    ),
                )
                ->values();
        }

        return view('marketing-suite::livewire.help-center.search', [
            'categories' => $categories,
            'results' => $results,
        ]);
    }
}
