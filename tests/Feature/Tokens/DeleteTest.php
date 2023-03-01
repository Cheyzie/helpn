<?php

namespace Tests\Feature\Tokens;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_delete_token()
    {
        $user = User::factory()->create();
        $user->createToken('test');

        $token = $user->tokens()->where('name', 'test')->first();

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/profile/tokens/{$token->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $token->id, 'name' => $token->name]);
    }

    public function test_authenticated_user_can_delete_own_token()
    {
        $user = User::factory()->create();
        $user->createToken('test');

        $token = $user->tokens()->where('name', 'test')->first();

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/profile/tokens/{$token->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->id, 'name' => $token->name]);
    }
}
