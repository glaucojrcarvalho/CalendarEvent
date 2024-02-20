<?php

namespace Tests\Unit;
use App\Domain\Entities\Event;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class OverlapsTest extends TestCase
{
    public function test_checkIsOverlapping()
    {
        $event = new Event([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'Test Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);

        $event2 = new Event([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'Test Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);

        $events = [$event, $event2];

        $this->assertTrue($event->checkIsOverlapping($events));
    }

    public function test_checkIsNotOverlapping()
    {
        $event = new Event([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'Test Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);

        $event2 = new Event([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'Test Description',
            'startDate' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->addWeek()->format('Y-m-d\TH:i:sP'),
        ]);

        $events = [$event, $event2];

        $this->assertFalse($event->checkIsOverlapping($events));
    }
}
