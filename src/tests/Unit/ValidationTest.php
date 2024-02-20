<?php

namespace Tests\Unit;
use App\Domain\Entities\Event;
use Carbon\Carbon;
use Exception;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    public function test_event_start_is_required(): void
    {
        $event = new Event([
            'title' => 'Event Title',
            'description' => 'Event Description',
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Start date is required');
        $event->validate();
    }

    public function test_event_end_is_required(): void
    {
        $event = new Event([
            'title' => 'Event Title',
            'description' => 'Event Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('End date is required');
        $event->validate();
    }

    public function test_event_start_must_be_in_iso_8601_format(): void
    {
        $event = new Event([
            'title' => 'Event Title',
            'description' => 'Event Description',
            'startDate' => '2024-01-01',
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not enough data available to satisfy format');
        $event->validate();
    }

    public function test_event_end_must_be_after_start(): void
    {
        $event = new Event([
            'title' => 'Event Title',
            'description' => 'Event Description',
            'startDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('End date must be after start date');
        $event->validate();
    }

    public function test_event_title_is_required(): void
    {
        $event = new Event([
            'description' => 'Event Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Title is required');
        $event->validate();
    }

    public function test_event_title_must_be_a_string(): void
    {
        $event = new Event([
            'title' => 123,
            'description' => 'Event Description',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Title must be a string');
        $event->validate();
    }

    public function test_event_description_must_be_a_string(): void
    {
        $event = new Event([
            'title' => 'Event Title',
            'description' => 123,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP')
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Description must be a string');
        $event->validate();
    }

    /**
     * @throws Exception
     */
    public function test_event_description_is_optional(): void
    {
        $event = new Event([
            'title' => 'Event Title',
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP')
        ]);

        $event->validate();
        $this->expectNotToPerformAssertions();
    }
}
