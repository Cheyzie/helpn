<?php

namespace Tests\Feature\Types;

use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_update_type()
    {
        $type = Type::factory()->create(['title' => 'testType']);

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/types/{$type->id}", [
                'title' => 'testTypeUpdated',
            ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('types', [
            'id' => $type->id,
            'title' => 'testTypeUpdated',
        ]);
    }

    public function test_unauthorized_user_cant_update_type()
    {
        $type = Type::factory()->create(['title' => 'testType']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/types/{$type->id}", [
                'title' => 'testTypeUpdated',
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('types', [
            'id' => $type->id,
            'title' => 'testTypeUpdated',
        ]);
    }

    public function test_admin_cant_update_nonexist_type()
    {

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/types/1111", [
                'title' => 'testNonExistTypeUpdated',
            ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('types', [
            'title' => 'testTypeUpdated',
        ]);
    }

    public function test_admin_can_update_type()
    {
        $type = Type::factory()->create(['title' => 'testType']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/types/{$type->id}", [
                'title' => 'testTypeUpdated',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('types', [
            'id' => $type->id,
            'title' => 'testTypeUpdated',
        ]);
    }
}
