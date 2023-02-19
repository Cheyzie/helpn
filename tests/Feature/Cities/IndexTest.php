<?php

namespace Tests\Feature\Cities;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_can_get_cities_list()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->get('/api/v1/cities');

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('cities', City::all()->count(), fn (AssertableJson $json) =>
                $json->has('id')->has('name')
            )
        );
    }
}
