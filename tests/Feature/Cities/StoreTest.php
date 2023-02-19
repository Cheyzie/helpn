<?php

namespace Tests\Feature\Cities;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_add_city()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/cities', ['name' => 'TestCity']);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('cities', [
            'name' => 'TestCity',
        ]);
    }

    public function test_banned_user_cant_add_city()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 3]));

        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/cities', ['name' => 'TestCity']);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('cities', [
            'name' => 'TestCity',
        ]);
    }

    public function test_authorized_user_cant_add_city()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/cities', ['name' => 'TestCity']);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cities', [
            'name' => 'TestCity',
        ]);
    }
}
