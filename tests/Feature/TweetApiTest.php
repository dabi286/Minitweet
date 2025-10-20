<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_fetch_tweets()
    {
        $user = \App\Models\User::factory()->create();

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $response = $this->getJson('/api/tweets');

        $response->assertStatus(200);
    }

    public function test_anyone_can_fetch_tweets()
    {
        $response = $this->getJson('/api/tweets');

        $response->assertStatus(200);
    }
}
