<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Resources\ShortUrlResource\Widgets;

use VasilGerginski\MarketingSuite\Models\EventSubmission;
use VasilGerginski\MarketingSuite\Models\ShortUrl;
use VasilGerginski\MarketingSuite\Models\ShortUrlVisit;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Database\Eloquent\Model;

class VisitTrendsWidget extends ChartWidget
{
    public ?Model $record = null;

    protected bool $isCollapsible = true;

    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('Performance Trends (Last 30 Days)');
    }

    public function getDescription(): ?string
    {
        return __('Daily visits and conversions comparison');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $visitsQuery = ShortUrlVisit::query()
            ->where(static function ($q): void {
                $q->whereNull('device_type')->orWhere('device_type', '!=', 'robot');
            });

        $submissionsQuery = EventSubmission::query()
            ->whereNotNull('short_url_visit_id');

        if ($this->record instanceof ShortUrl) {
            $visitsQuery->where('short_url_id', $this->record->id);

            $visitIds = ShortUrlVisit::query()
                ->where('short_url_id', $this->record->id)
                ->select('id');

            $submissionsQuery->whereIn('short_url_visit_id', $visitIds);
        }

        $visitTrend = Trend::query($visitsQuery)
            ->dateColumn('visited_at')
            ->between($start, $end)
            ->perDay()
            ->count();

        $convTrend = Trend::query($submissionsQuery)
            ->dateColumn('created_at')
            ->between($start, $end)
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => __('Visits'),
                    'data' => $visitTrend->map(static fn ($value) => $value->aggregate)->toArray(),
                    'borderColor' => 'rgba(21, 101, 192, 1)',
                    'backgroundColor' => 'rgba(21, 101, 192, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => __('Conversions'),
                    'data' => $convTrend->map(static fn ($value) => $value->aggregate)->toArray(),
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $visitTrend->map(static fn ($value) => Carbon::parse($value->date)->format('M d'))->toArray(),
        ];
    }
}
