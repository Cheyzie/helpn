<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_delete_profile()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->delete('/api/v1/profile');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_delete_own_profile() {
        $user = User::factory()->create(['role_id' => 2]);

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->delete('/api/v1/profile');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
