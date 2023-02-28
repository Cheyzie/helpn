<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_update_profile()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->patch('/api/v1/profile', ['name' => 'New test name']);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_update_own_profile()
    {
        $user = User::factory()->create(['role_id' => 2]);

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->patch('/api/v1/profile', ['name' => 'New test name']);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New test name',
        ]);
    }
}
