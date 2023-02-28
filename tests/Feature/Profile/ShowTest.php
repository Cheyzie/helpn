<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_get_profile()
    {
        $response = $this->withHeader('accept', 'application/json')->get('/api/v1/profile');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_own_profile()
    {
        $user = User::factory()->create(['role_id' => 2]);

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')->get('/api/v1/profile');

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('profile', fn (AssertableJson $json) =>
                $json->where('id', $user->id)
                    ->has('email')
                    ->has('name')
                    ->etc()
            )
        );
    }
}
