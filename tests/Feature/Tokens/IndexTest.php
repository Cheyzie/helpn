<?php

namespace Tests\Feature\Tokens;

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

    public function test_unauthenticated_user_cant_get_tokens()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->get('/api/v1/profile/tokens');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_tokens()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $user->createToken('test');

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->get('/api/v1/profile/tokens');

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('tokens', $user->tokens->count(), fn (AssertableJson $json) =>
                $json->has('id')->where('name', 'test')->missing('token')->etc()));
    }
}
