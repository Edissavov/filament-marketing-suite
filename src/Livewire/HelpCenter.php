<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use VasilGerginski\MarketingSuite\Models\HelpArticle;
use VasilGerginski\MarketingSuite\Models\HelpArticleFeedback;
use VasilGerginski\MarketingSuite\Models\HelpCategory;

#[Layout('marketing-suite::components.layouts.guest')]
class HelpCenter extends Component
{
    public ?HelpCategory $category = null;

    public ?HelpArticle $article = null;

    public function mount(?HelpCategory $category = null, ?HelpArticle $article = null): void
    {
        if ($category?->exists && $category->is_active) {
            $this->category = $category;
        }

        if ($article?->exists && $article->is_published) {
            $this->article = $article;
        }
    }

    public function submitFeedback(bool $isHelpful): void
    {
        if (! $this->article) {
            return;
        }

        $ip = request()->ip();

        $existing = HelpArticleFeedback::query()
            ->where('help_article_id', $this->article->id)
            ->where('ip_address', $ip)
            ->first();

        if ($existing) {
            if ($existing->is_helpful === $isHelpful) {
                $existing->delete();
            } else {
                $existing->update(['is_helpful' => $isHelpful]);
            }
        } else {
            HelpArticleFeedback::create([
                'help_article_id' => $this->article->id,
                'ip_address' => $ip,
                'user_id' => auth()->id(),
                'is_helpful' => $isHelpful,
            ]);
        }
    }

    public function render(): View
    {
        $categories = HelpCategory::query()
            ->where('is_active', true)
            ->withCount(['articles' => static fn ($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')
            ->get();

        $articles = null;
        if ($this->category) {
            $articles = $this->category
                ->articles()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->get();
        }

        $feedbackStats = null;
        $userFeedback = null;
        $previousArticle = null;
        $nextArticle = null;

        if ($this->article && $this->category) {
            $feedbackStats = [
                'total' => $this->article->feedback()->count(),
                'helpful' => $this->article
                    ->feedback()
                    ->where('is_helpful', true)
                    ->count(),
            ];

            $userFeedback = HelpArticleFeedback::query()
                ->where('help_article_id', $this->article->id)
                ->where('ip_address', request()->ip())
                ->first();

            $previousArticle = HelpArticle::query()
                ->where('help_category_id', $this->category->id)
                ->where('is_published', true)
                ->where('sort_order', '<', $this->article->sort_order)
                ->orderByDesc('sort_order')
                ->first();

            $nextArticle = HelpArticle::query()
                ->where('help_category_id', $this->category->id)
                ->where('is_published', true)
                ->where('sort_order', '>', $this->article->sort_order)
                ->orderBy('sort_order')
                ->first();
        }

        return view('marketing-suite::livewire.help-center', [
            'categories' => $categories,
            'articles' => $articles,
            'feedbackStats' => $feedbackStats,
            'userFeedback' => $userFeedback,
            'previousArticle' => $previousArticle,
            'nextArticle' => $nextArticle,
        ]);
    }
}
