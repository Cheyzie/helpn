<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_change_user_data()
    {
        $user = User::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/users/{$user->id}", [
                'name' => 'unauthenticated'
            ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => 'unauthenticated',
        ]);
    }

    public function test_unauthorized_user_cant_change_user_data()
    {
        $user = User::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/users/{$user->id}", [
                'name' => 'unauthenticated'
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => 'unauthenticated',
        ]);
    }

    public function test_user_cant_change_own_role_data()
    {
        $user = User::factory()->create(['role_id'=>2]);

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/users/{$user->id}", [
                'name' => 'new name',
                'role_id' => 3,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'role_id' => 3,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'new name',
        ]);
    }

    public function test_admin_cant_change_nonexist_user_data()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/users/1234", [
                'name' => 'non exist'
            ]);

        $response->assertStatus(404);
    }

    public function test_user_can_change_own_user_data()
    {
        $user = User::all()->first();

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/users/{$user->id}", [
                'name' => 'new name'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'new name',
        ]);
    }

    public function test_admin_can_change_exist_user_data()
    {
        $user = User::factory()->create(['role_id' => 2]);

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/users/{$user->id}", [
                'name' => 'new name',
                'role_id' => 3,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'new name',
            'role_id' => 3,
        ]);
    }
}
