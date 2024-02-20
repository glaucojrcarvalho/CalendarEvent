<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Exception;
use Ramsey\Uuid\Uuid;

class Event
{
    private mixed $id;
    private mixed $uuid;
    private mixed $title;
    private mixed $description;
    private mixed $startDate;
    private mixed $endDate;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->uuid = $data['uuid'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->startDate = $data['startDate'] ?? null;
        $this->endDate = $data['endDate'] ?? null;
    }

    public static function factory(): Event
    {
        return new Event([
            'uuid' => Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'Test Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function create(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }

    public function validate(): void
    {
        if (!$this->title) {
            throw new Exception('Title is required');
        }

        if (!is_string($this->title)) {
            throw new Exception('Title must be a string');
        }

        if (!$this->startDate) {
            throw new Exception('Start date is required');
        }

        if (!Carbon::createFromFormat('Y-m-d\TH:i:sP', $this->startDate)) {
            throw new Exception('Not enough data available to satisfy format');
        }

        if (!$this->endDate) {
            throw new Exception('End date is required');
        }

        if (!Carbon::createFromFormat('Y-m-d\TH:i:sP', $this->endDate)) {
            throw new Exception('Not enough data available to satisfy format');
        }

        if (Carbon::createFromFormat('Y-m-d\TH:i:sP', $this->endDate) < Carbon::createFromFormat('Y-m-d\TH:i:sP', $this->startDate)) {
            throw new Exception('End date must be after start date');
        }

        if ($this->description && !is_string($this->description)) {
            throw new Exception('Description must be a string');
        }
    }

    public function checkIsOverlapping(array $events): bool
    {
        if (count($events) < 2) {
            return false;
        }

        $eventTimestamps = [];
        foreach ($events as $event) {
            $eventTimestamps[] = [
                'start' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $event->getStartDate())->timestamp,
                'end' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $event->getEndDate())->timestamp
            ];
        }

        usort($eventTimestamps, function ($a, $b) {
            return $a['start'] - $b['start'];
        });

        for ($i = 0; $i < count($eventTimestamps) - 1; $i++) {
            if ($eventTimestamps[$i]['end'] > $eventTimestamps[$i + 1]['start']) {
                return true;
            }
        }

        return false;
    }
}
