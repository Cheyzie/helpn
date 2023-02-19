<?php

namespace Tests\Feature\Cities;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_delete_city()
    {
        $city = City::factory()->create(['name' => 'Boryspil']);

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/cities/{$city->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('cities', [
            'id' => $city->id,
            'name' => $city->name,
        ]);
    }

    public function test_unauthorized_user_cant_delete_city()
    {
        $city = City::factory()->create(['name' => 'Boryspil']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/cities/{$city->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('cities', [
            'id' => $city->id,
            'name' => $city->name,
        ]);
    }

    public function test_admin_cant_delete_nonexist_city()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/cities/1234");

        $response->assertStatus(404);
    }

    public function test_admin_can_delete_exist_city()
    {
        $city = City::factory()->create(['name' => 'Boryspil']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/cities/{$city->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('cities', [
            'id' => $city->id,
            'name' => $city->name,
        ]);
    }
}
