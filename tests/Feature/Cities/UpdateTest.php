<?php

namespace Tests\Feature\Cities;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_edit_city()
    {
        $city = City::factory()->create(['name' => 'Boryspil']);

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/cities/{$city->id}", ['name' => 'New Boryspil']);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('cities', [
           'id' => $city->id,
            'name' => 'New Boryspil',
        ]);
    }

    public function test_unauthorized_user_cant_edit_city()
    {
        $city = City::factory()->create(['name' => 'Boryspil']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/cities/{$city->id}", ['name' => 'New Boryspil']);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('cities', [
            'id' => $city->id,
            'name' => 'New Boryspil',
        ]);
    }

    public function test_admin_cant_edit_nonexist_city()
    {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/cities/1234", ['name' => 'New Boryspil']);

        $response->assertStatus(404);
    }

    public function test_admin_can_edit_exist_city()
    {
        $city = City::factory()->create(['name' => 'Boryspil']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/cities/{$city->id}", ['name' => 'New Boryspil']);

        $response->assertStatus(200);

        $this->assertDatabaseHas('cities', [
            'id' => $city->id,
            'name' => 'New Boryspil',
        ]);
    }
}
