<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets;

use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Models\ShortUrlVisit;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class TopBrowsersWidget extends ChartWidget
{
    public ?Model $record = null;

    protected bool $isCollapsible = true;

    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('Browser Distribution');
    }

    public function getDescription(): ?string
    {
        return __('Visits by browser (excluding robots)');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $query = ShortUrlVisit::query()
            ->where(static function ($q): void {
                $q->whereNull('device_type')->orWhere('device_type', '!=', 'robot');
            })
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(8);

        if ($this->record instanceof ShortUrl) {
            $query->where('short_url_id', $this->record->id);
        }

        $data = $query->get();

        $colors = [
            'rgba(21, 101, 192, 0.8)',
            'rgba(60, 140, 236, 0.8)',
            'rgba(34, 197, 94, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(20, 184, 166, 0.8)',
            'rgba(249, 115, 22, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => $data->keys()->map(static fn ($i) => $colors[$i % count($colors)])->toArray(),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $data->pluck('browser')->toArray(),
        ];
    }
}
