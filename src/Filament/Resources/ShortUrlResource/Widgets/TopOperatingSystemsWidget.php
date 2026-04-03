<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets;

use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Models\ShortUrlVisit;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class TopOperatingSystemsWidget extends ChartWidget
{
    public ?Model $record = null;

    protected bool $isCollapsible = true;

    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('Operating System Distribution');
    }

    public function getDescription(): ?string
    {
        return __('Visits by OS (excluding robots)');
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
            ->whereNotNull('operating_system')
            ->selectRaw('operating_system, COUNT(*) as count')
            ->groupBy('operating_system')
            ->orderByDesc('count')
            ->limit(8);

        if ($this->record instanceof ShortUrl) {
            $query->where('short_url_id', $this->record->id);
        }

        $data = $query->get();

        $colors = [
            'rgba(0, 56, 121, 0.8)',
            'rgba(21, 101, 192, 0.8)',
            'rgba(60, 140, 236, 0.8)',
            'rgba(255, 182, 15, 0.8)',
            'rgba(34, 197, 94, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(236, 72, 153, 0.8)',
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
            'labels' => $data->pluck('operating_system')->toArray(),
        ];
    }
}
