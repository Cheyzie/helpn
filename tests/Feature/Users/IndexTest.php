<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_retrieve_users_list()
    {
        $response = $this->withHeader('accept', 'application/json')->get('/api/v1/users');

        $response->assertStatus(401);
    }

    public function test_unauthorized_user_cant_retrieve_users_list()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')->get('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_retrieve_users_list() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')->get('/api/v1/users');

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('users', User::all()->count(), fn (AssertableJson $json) =>
                $json->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('role')
                    ->etc()
            )
        );
    }
}
