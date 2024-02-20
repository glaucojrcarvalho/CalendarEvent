<?php

namespace App\Infrastructure\Database;

use App\Domain\Repositories\EventRepository;
use App\Domain\Entities\Event;
use App\Models\EventModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as paginator;
use Exception;

class DatabaseEventRepository implements EventRepository
{
    public function create(Event $event): void
    {
        if ($this->checkIsOverlapping($event) !== null) {
            throw new Exception('Event is overlapping with another event');
        }

        EventModel::query()->create([
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'start_date' => $event->getStartDate(),
            'end_date' => $event->getEndDate()
        ]);
    }

    private function checkIsOverlapping(Event $event, $uuid = null): object|array|null
    {
        $overlapping = EventModel::query()
            ->where('start_date', '<=', $event->getEndDate())
            ->where('end_date', '>=', $event->getStartDate());

        if ($uuid) {
            $overlapping->where('uuid', '!=', $uuid);
        }

        return $overlapping->first();
    }
    public function update(Event $event): void
    {
        if ($this->checkIsOverlapping($event, $event->getUuid())) {
            throw new \Exception('Event is overlapping with another event');
        }

        EventModel::query()->where('uuid', $event->getUuid())->update([
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'start_date' => $event->getStartDate(),
            'end_date' => $event->getEndDate()
        ]);
    }
    public function list($data): paginator
    {
        $query = EventModel::query();
        $query->when(isset($data['startDate']), function ($query) use ($data) {
            $query->where('start_date', '>=', $data['startDate']);
        });
        $query->when(isset($data['endDate']), function ($query) use ($data) {
            $query->where('end_date', '<=', $data['endDate']);
        });

        return $query->paginate();
    }

    public function delete(Event $event): void
    {
        EventModel::query()->where('uuid', $event->getUuid())->delete();
    }
}
