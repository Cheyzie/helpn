<?php

namespace Tests\Feature\Types;

use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_delete_type()
    {
        $type = Type::factory()->create(['title' => 'testType']);

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/types/{$type->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('types', ['id' => $type->id]);
    }

    public function test_unauthorized_user_cant_delete_type()
    {
        $type = Type::factory()->create(['title' => 'testType']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/types/{$type->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('types', ['id' => $type->id]);
    }

    public function test_admin_cant_delete_nonexist_type()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/types/1234");

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_type()
    {
        $type = Type::factory()->create(['title' => 'testType']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/types/{$type->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('types', ['id' => $type->id]);
    }

}
