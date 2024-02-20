<?php

namespace Tests\Unit;

use App\Application\UseCases\DeleteEventUseCase as ApplicationDeleteEventUseCase;
use Tests\TestCase;

class DeleteEventUseCase extends TestCase
{
    public function test_delete_event()
    {
        $event = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()
        ];

        $mockDeleteEventUseCase = $this->createMock(ApplicationDeleteEventUseCase::class);
        $mockDeleteEventUseCase->expects($this->once())
            ->method('delete')
            ->with($event['uuid']);

        $mockDeleteEventUseCase->delete($event['uuid']);
        $this->assertTrue(true);
    }
}
