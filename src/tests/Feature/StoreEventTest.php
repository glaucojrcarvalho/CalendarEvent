<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreEventTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_store_event_with_valid_data()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'event created successfully',
            ]);

        $data['start_date'] = $data['startDate'];
        $data['end_date'] = $data['endDate'];
        unset($data['startDate'], $data['endDate']);

        $this->assertDatabaseHas('calendar_events', $data);
    }

    public function test_store_event_with_invalid_data()
    {
        $data = [
            'description' => $this->faker->paragraph,
            'startDate' => $this->faker->date,
            'endDate' => $this->faker->date,
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'title',
            ]);
    }

    public function test_store_event_with_invalid_date_range()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d H:i:s'),
            'endDate' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'endDate',
            ]);
    }

    public function test_store_event_with_invalid_date_format()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('d-m-Y'),
            'endDate' => Carbon::now()->addDay()->format('d-m-Y'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'startDate',
                'endDate',
            ]);
    }

    public function test_store_event_with_recurring_pattern()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => true,
            'frequency' => 'daily',
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'event created successfully',
            ]);

        $data['start_date'] = $data['startDate'];
        $data['end_date'] = $data['endDate'];
        unset($data['startDate'], $data['endDate'], $data['recurringPattern'], $data['frequency'], $data['repeatUntil']);

        $this->assertDatabaseHas('calendar_events', $data);
    }

    public function test_store_event_with_invalid_recurring_pattern()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => true,
            'frequency' => 'invalid',
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'frequency',
            ]);
    }

    public function test_store_event_with_invalid_repeat_until_date()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => true,
            'frequency' => 'daily',
            'repeatUntil' => Carbon::now()->subDay()->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'repeatUntil',
            ]);
    }

    public function test_store_event_with_overlapping_date()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->subDay()->format('Y-m-d\TH:i:sP'),
        ];

        $this->postJson('/api/events', $data);

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'endDate',
            ]);
    }

    public function test_store_event_with_overlapping_date_with_existing_event()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
        ];

        $this->postJson('/api/events', $data);

        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDays(2)->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(500);
    }

    public function test_store_event_with_overlapping_date_with_existing_event_and_recurring_pattern()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => true,
            'frequency' => 'daily',
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $this->postJson('/api/events', $data);

        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'startDate' => Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'),
            'endDate' => Carbon::now()->addDays(2)->format('Y-m-d\TH:i:sP'),
            'recurringPattern' => true,
            'frequency' => 'daily',
            'repeatUntil' => Carbon::now()->addWeek()->format('Y-m-d\TH:i:sP'),
        ];

        $response = $this->postJson('/api/events', $data);

        $response->assertStatus(500);
    }
}
