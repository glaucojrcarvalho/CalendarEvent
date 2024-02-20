<?php

namespace Tests\Feature;

use App\Domain\Entities\Event;
use App\Http\Middleware\UuidExists;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_update_event_with_valid_data()
    {
        $event = Event::factory()->create();

        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $data = [
            'title' => $this->faker->sentence,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'description' => $this->faker->paragraph
        ];

        $response = $this->putJson("/api/events/{$event['uuid']}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'event updated successfully',
            ]);
    }

    public function test_update_event_with_invalid_data()
    {
        $event = Event::factory()->create();

        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $data = [
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'description' => $this->faker->paragraph,
            ];

        $response = $this->putJson("/api/events/{$event['uuid']}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'title'
            ]);
    }

    public function test_update_event_with_invalid_date_range()
    {
        $event = Event::factory()->create();

        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $data = [
            'title' => $this->faker->sentence,
            'startDate' => Carbon::now()->format('Y-m-d H:i:s'),
            'endDate' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
            'description' => $this->faker->paragraph
        ];

        $response = $this->putJson("/api/events/{$event['uuid']}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'startDate',
                'endDate'
            ]);
    }

    public function test_update_event_with_invalid_date_format()
    {
        $event = Event::factory()->create();

        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $data = [
            'title' => $this->faker->sentence,
            'startDate' => Carbon::now()->format('d-m-Y'),
            'endDate' => Carbon::now()->addDay()->format('d-m-Y'),
            'description' => $this->faker->paragraph
        ];

        $response = $this->putJson("/api/events/{$event['uuid']}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'startDate',
                'endDate'
            ]);
    }

    public function test_update_event_with_overlapping_another_event()
    {
        $event = Event::factory()->create();
        $anotherEvent = Event::factory()->create();
        $anotherEvent['uuid'] = $this->faker->uuid;

        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $this->postJson('/api/events', $event);
        $this->postJson('/api/events', $anotherEvent);

        $data = [
            'title' => $this->faker->sentence,
            'startDate' => $anotherEvent['startDate'],
            'endDate' => $anotherEvent['endDate'],
            'description' => $this->faker->paragraph
        ];

        $response = $this->putJson("/api/events/{$event['uuid']}", $data);

        $response->assertStatus(500);
    }

    public function test_update_event_with_recurring_pattern()
    {
        $event = Event::factory()->create();

        $mockMiddleware = \Mockery::mock(UuidExists::class);
        $mockMiddleware->shouldReceive('handle')->once()->andReturnUsing(function ($request, $next) {
            return $next($request);
        });
        $this->app->instance(UuidExists::class, $mockMiddleware);

        $data = [
            'title' => $this->faker->sentence,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'description' => $this->faker->paragraph,
            'recurringPattern' => true,
            'frequency' => 'daily',
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->putJson("/api/events/{$event['uuid']}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'event updated successfully',
            ]);
    }
}
