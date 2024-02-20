<?php

namespace Tests\Unit;

use App\Application\UseCases\UpdateEventUseCase as ApplicationUpdateEventUseCase;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\Exception;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UpdateEventUseCase extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_update_event()
    {
        $event = [
            'uuid' => Uuid::uuid4(),
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ];

        $mockUpdateEventUseCase = $this->createMock(ApplicationUpdateEventUseCase::class);
        $mockUpdateEventUseCase->expects($this->once())
            ->method('update')
            ->with($event);

        $mockUpdateEventUseCase->update($event, $event['uuid']);

        $this->assertTrue(true);
    }
}
