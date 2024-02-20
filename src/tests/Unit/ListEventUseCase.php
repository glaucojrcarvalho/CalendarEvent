<?php

namespace Tests\Unit;

use App\Application\UseCases\ListEventUseCase as ApplicationListEventUseCase;
use Carbon\Carbon;
use Tests\TestCase;

class ListEventUseCase extends TestCase
{
    public function test_list_event()
    {
        $event = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ];

        $mockListEventUseCase = $this->createMock(ApplicationListEventUseCase::class);
        $mockListEventUseCase->expects($this->once())
            ->method('list')
            ->with($event);

        $mockListEventUseCase->list($event);
        $this->assertTrue(true);
    }
}
