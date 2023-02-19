<?php

namespace Tests\Feature\Types;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_create_type()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/types', [
                'title' => 'Supplies'
            ]);

        $response->assertStatus(401);
    }

    public function test_unauthorized_user_cant_create_type()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/types', [
                'title' => 'Supplies'
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_cant_create_type_with_invalid_data()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/types', []);

        $response->assertStatus(422);
    }

    public function test_admin_can_create_type()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/types', [
                'title' => 'testSupplies',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('types', [
            'title' => 'testSupplies',
        ]);
    }
}
