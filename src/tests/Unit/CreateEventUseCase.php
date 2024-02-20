<?php

namespace Tests\Unit;

use App\Application\UseCases\CreateEventUseCase as ApplicationCreateEventUseCase;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class CreateEventUseCase extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_create_event()
    {
        $event = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ];

        $mockCreateEventUseCase = $this->createMock(ApplicationCreateEventUseCase::class);
        $mockCreateEventUseCase->expects($this->once())
            ->method('create')
            ->with($event);

        $mockCreateEventUseCase->create($event);
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function test_create_recurring_event()
    {
        $event = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => 'daily',
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $mockCreateEventUseCase = $this->createMock(ApplicationCreateEventUseCase::class);
        $mockCreateEventUseCase->expects($this->once())
            ->method('create')
            ->with($event);

        $mockCreateEventUseCase->create($event);
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function test_create_recurring_event_with_no_repeat_until()
    {
        $event = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => 'daily',
        ];

        $mockCreateEventUseCase = $this->createMock(ApplicationCreateEventUseCase::class);
        $mockCreateEventUseCase->expects($this->once())
            ->method('create')
            ->with($event);

        $mockCreateEventUseCase->create($event);
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function test_create_recurring_event_with_no_recurring_pattern()
    {
        $event = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $mockCreateEventUseCase = $this->createMock(ApplicationCreateEventUseCase::class);
        $mockCreateEventUseCase->expects($this->once())
            ->method('create')
            ->with($event);

        $mockCreateEventUseCase->create($event);
        $this->assertTrue(true);
    }
}
