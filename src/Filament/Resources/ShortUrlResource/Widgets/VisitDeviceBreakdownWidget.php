<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Models\ShortUrlVisit;

class VisitDeviceBreakdownWidget extends ChartWidget
{
    public ?Model $record = null;

    protected bool $isCollapsible = true;

    protected int | string | array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('Device Breakdown');
    }

    public function getDescription(): ?string
    {
        return __('Visits by device type (excluding robots)');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $deviceTypes = ['desktop', 'mobile', 'tablet'];

        $query = ShortUrlVisit::query()
            ->whereIn('device_type', $deviceTypes)
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type');

        if ($this->record instanceof ShortUrl) {
            $query->where('short_url_id', $this->record->id);
        }

        $raw = $query->get()->keyBy('device_type');

        $counts = array_map(
            static fn (string $type) => (int) ($raw[$type]->count ?? 0),
            $deviceTypes
        );

        $labels = [
            __('Desktop'),
            __('Mobile'),
            __('Tablet'),
        ];

        $colors = [
            'rgba(21, 101, 192, 0.8)',
            'rgba(60, 140, 236, 0.8)',
            'rgba(255, 182, 15, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'label' => __('Visits'),
                    'data' => $counts,
                    'backgroundColor' => $colors,
                    'borderColor' => array_map(
                        static fn (string $c) => str_replace('0.8', '1', $c),
                        $colors
                    ),
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
