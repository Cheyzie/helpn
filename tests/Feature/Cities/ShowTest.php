<?php

namespace Tests\Feature\Cities;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_cant_get_nonexist_city()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/cities/1234");

        $response->assertStatus(404);
    }

    public function test_can_get_exist_city()
    {
        $city = City::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/cities/{$city->id}");

        $response->assertStatus(200);

        $response->assertJson(['city' => $city->toArray()]);
    }
}
