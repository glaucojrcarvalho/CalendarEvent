<?php

namespace App\Application\UseCases;
use App\Domain\Repositories\EventRepository;
use App\Domain\Entities\Event;

class UpdateEventUseCase
{
    private EventRepository $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function update(array $data, string $uuid): void
    {
        $data['uuid'] = $uuid;
        $event = new Event($data);
        $this->eventRepository->update($event);
    }
}
