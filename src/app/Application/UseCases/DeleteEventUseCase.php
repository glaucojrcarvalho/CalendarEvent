<?php

namespace App\Application\UseCases;
use App\Domain\Repositories\EventRepository;
use App\Domain\Entities\Event;
class DeleteEventUseCase
{
    private EventRepository $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function delete(string $uuid): void
    {
        $event = new Event(['uuid' => $uuid]);
        $this->eventRepository->delete($event);
    }
}
