<?php

namespace Tests\Feature\Types;

use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_cant_get_nonexist_type()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/types/1234");

        $response->assertStatus(404);
    }

    public function test_can_get_exist_type()
    {
        $type = Type::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->get("/api/v1/types/{$type->id}");

        $response->assertStatus(200);

        $response->assertJson(['type' => [
            'id' => $type->id,
            'title' => $type->title,
        ]]);
    }
}
