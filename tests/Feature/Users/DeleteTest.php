<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_users_cant_delete_user()
    {
        $user = User::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/users/{$user->id}");

        $response->assertStatus(401);
    }

    public function test_unauthorized_user_cant_delete_user() {
        $user = User::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/users/{$user->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_admin_cant_delete_nonexist_user() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/users/1111");

        $response->assertStatus(404);
    }

    public function test_user_can_delete_own_account() {
        $user = User::all()->first();

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_can_delete_any_exist_user() {
        $user = User::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }


}
