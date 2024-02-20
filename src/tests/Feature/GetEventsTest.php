<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetEventsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_events_with_valid_data()
    {
        $response = $this->getJson('/api/events');

        $response->assertStatus(200);
    }

    public function test_get_events_with_range_date()
    {
        $response = $this->getJson('/api/events?startDate=' . Carbon::now()->format('Y-m-d\TH:i:sP') . '&endDate=' . Carbon::now()->addDay()->format('Y-m-d\TH:i:sP'));

        $response->assertStatus(200);
    }
}
