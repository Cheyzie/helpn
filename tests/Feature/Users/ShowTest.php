<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_get_user_info()
    {
        $user = User::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/users/{$user->id}");

        $response->assertStatus(401);
    }

    public function test_unauthorized_user_cant_get_user_info() {
        $user = User::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/users/{$user->id}");

        $response->assertStatus(403);
    }

    public function test_admin_cant_get_nonexist_user_info() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->get('/api/v1/users/1234');

        $response->assertStatus(404);
    }

    public function test_admin_can_get_exist_user_info() {
        $user = User::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/users/{$user->id}");

        $response->assertStatus(200);

        $response->assertJson(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
            ],
        ]]);
    }

    public function test_user_can_get_own_user_info() {
        $user = User::all()->first();

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/users/{$user->id}");

        $response->assertStatus(200);

        $response->assertJson(['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
            ],
        ]]);
    }
}
