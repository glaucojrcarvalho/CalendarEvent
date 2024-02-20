<?php

namespace Tests\Feature;

use App\Domain\Entities\Event;
use App\Http\Middleware\UuidExists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteEventsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_delete_event_with_valid_data()
    {
        $event = Event::factory()->create();
        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $response = $this->deleteJson("/api/events/{$event['uuid']}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'event deleted successfully',
            ]);
    }

    public function test_delete_event_with_invalid_uuid()
    {
        $response = $this->deleteJson("/api/events/invalid-uuid");

        $response->assertStatus(404);
    }

    public function test_delete_event_with_invalid_uuid_format()
    {
        $response = $this->deleteJson("/api/events/123");

        $response->assertStatus(404);
    }
}
