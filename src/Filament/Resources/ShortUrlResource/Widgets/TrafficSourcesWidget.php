<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets;

use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Models\ShortUrlVisit;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class TrafficSourcesWidget extends ChartWidget
{
    public ?Model $record = null;

    protected bool $isCollapsible = true;

    protected int|string|array $columnSpan = 2;

    public function getHeading(): string
    {
        return __('Top Traffic Sources');
    }

    public function getDescription(): ?string
    {
        return __('Top referer URLs driving visits');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $query = ShortUrlVisit::query()
            ->where(static function ($q): void {
                $q->whereNull('device_type')->orWhere('device_type', '!=', 'robot');
            })
            ->whereNotNull('referer_url')
            ->where('referer_url', '!=', '')
            ->selectRaw('referer_url, COUNT(*) as count')
            ->groupBy('referer_url')
            ->orderByDesc('count')
            ->limit(10);

        if ($this->record instanceof ShortUrl) {
            $query->where('short_url_id', $this->record->id);
        }

        $data = $query->get();

        $labels = $data->map(static function ($row): string {
            $parsed = parse_url((string) $row->referer_url);

            return $parsed['host'] ?? (string) $row->referer_url;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('Visits'),
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(21, 101, 192, 0.75)',
                    'borderColor' => 'rgba(21, 101, 192, 1)',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
