<?php

namespace App\Application\UseCases;
use App\Domain\Repositories\EventRepository;
use App\Domain\Services\EventRecurringService as EventRepeaterService;
use App\Domain\Entities\Event;
use Illuminate\Support\Facades\DB;

class CreateEventUseCase
{
    private EventRepository $eventRepository;
    private EventRepeaterService $eventRepeaterService;
    public function __construct(EventRepository $eventRepository, EventRepeaterService $eventRepeaterService)
    {
        $this->eventRepository = $eventRepository;
        $this->eventRepeaterService = $eventRepeaterService;
    }

    public function create(array $data): void
    {
        DB::transaction(function () use ($data) {
            $event = new Event($data);
            $this->eventRepository->create($event);

            if(isset($data['recurringPattern'])) {
                $events = $this->eventRepeaterService->repeat($data);
                foreach($events as $event) {
                    $this->eventRepository->create($event);
                }
            }
        });
    }
}
