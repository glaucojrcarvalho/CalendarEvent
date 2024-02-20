<?php

namespace App\Application\UseCases;
use App\Domain\Repositories\EventRepository;
class ListEventUseCase
{
    private EventRepository $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function list(array $data)
    {
        return $this->eventRepository->list($data);
    }
}
