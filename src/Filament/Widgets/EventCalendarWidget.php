<?php

declare(strict_types=1);

namespace VasilGerginski\MarketingSuite\Filament\Widgets;

use VasilGerginski\MarketingSuite\Filament\Resources\EventResource;
use VasilGerginski\MarketingSuite\Models\Event;
use Carbon\Carbon;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\Filament\Actions\ViewAction;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;

class EventCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    protected bool $eventClickEnabled = true;

    protected bool $dateClickEnabled = true;

    protected bool $eventDragEnabled = true;

    protected ?string $defaultEventClickAction = 'edit';

    protected function getEvents(FetchInfo $info): Collection|array
    {
        return Event::query()
            ->where('is_active', true)
            ->whereNotNull('event_date')
            ->whereDate('event_date', '>=', $info->start)
            ->whereDate('event_date', '<=', $info->end)
            ->get()
            ->map(static function (Event $event): CalendarEvent {
                $start = Carbon::parse($event->event_date);
                if ($event->event_time) {
                    $start->setTimeFromTimeString($event->event_time);
                }

                $end = Carbon::parse($event->event_date);
                if ($event->event_end_time) {
                    $end->setTimeFromTimeString($event->event_end_time);
                } elseif ($event->event_time) {
                    $end = $start->copy()->addHour();
                }

                $color = match ($event->event_type) {
                    'lead_generation' => '#1565C0',
                    'event_registration' => '#F59E0B',
                    'newsletter' => '#10B981',
                    'consultation' => '#0EA5E9',
                    default => '#6B7280',
                };

                return CalendarEvent::make($event)
                    ->title($event->name)
                    ->start($start)
                    ->end($end)
                    ->backgroundColor($color)
                    ->allDay(! $event->event_time);
            });
    }

    protected function onDateClick(DateClickInfo $info): void
    {
        $this->mountAction('createEvent');
    }

    protected function getEventClickContextMenuActions(): array
    {
        return [
            $this->viewAction(),
            $this->editAction(),
            $this->deleteAction(),
        ];
    }

    public function createEventAction(): CreateAction
    {
        return $this->createAction(Event::class)
            ->schema(static fn ($schema) => EventResource::form($schema));
    }

    public function editAction(): EditAction
    {
        return EditAction::make('edit')
            ->schema(static fn ($schema) => EventResource::form($schema));
    }

    public function viewAction(): ViewAction
    {
        return ViewAction::make('view')
            ->schema(static fn ($schema) => EventResource::form($schema));
    }

    public function getHeading(): ?string
    {
        return __('Event Calendar');
    }

    public function getHeaderActions(): array
    {
        return [];
    }
}
