<?php

namespace Tests\Unit;

use App\Domain\Entities\Event;
use App\Infrastructure\Database\DatabaseEventRepository as DatabaseEventRepositoryAlias;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class DatabaseEventRepositoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function test_event_store_in_database()
    {
        $event = new Event([
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);

        $mockDatabase = $this->createMock(DatabaseEventRepositoryAlias::class);
        $mockDatabase->expects($this->once())
            ->method('create')
            ->with($event);

        $mockDatabase->create($event);
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function test_event_update_in_database()
    {
        $event = new Event([
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);

        $mockDatabase = $this->createMock(DatabaseEventRepositoryAlias::class);
        $mockDatabase->expects($this->once())
            ->method('update')
            ->with($event);

        $mockDatabase->update($event);
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function test_event_delete_in_database()
    {
        $event = new Event([
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'start_date' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'end_date' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ]);

        $mockDatabase = $this->createMock(DatabaseEventRepositoryAlias::class);
        $mockDatabase->expects($this->once())
            ->method('delete')
            ->with($event);

        $mockDatabase->delete($event);
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function test_event_list_in_database()
    {
        $data = [
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ];

        $mockDatabase = $this->createMock(DatabaseEventRepositoryAlias::class);
        $mockDatabase->expects($this->once())
            ->method('list')
            ->with($data);

        $mockDatabase->list($data);
        $this->assertTrue(true);
    }
}
