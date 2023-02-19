<?php

namespace Tests\Feature\Types;

use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_can_retrieve_types_list()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->get('/api/v1/types');

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('types', Type::all()->count(), fn (AssertableJson $json) =>
                $json->has('id')->has('title')->etc()
            )
        );
    }
}
