<?php

namespace Tests\Feature\Bills;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_store()
    {
        $response = $this->withHeader('accept', 'application/json')
            ->post('/api/v1/bills',
            [
                'title'=>'test bill`s from unauthenticated user title',
                'details' => 'test bill`s from unauthenticated user detail',
                'contacts' => 'unauthenticated user contacts',
                'city_id' => 1,
                'type_id' => 1,
            ]
        );

        $response->assertStatus(401);

        $this->assertDatabaseMissing('bills', [
            'title'=>'test bill`s from unauthenticated user title',
            'details' => 'test bill`s from unauthenticated user detail',
            'contacts' => 'unauthenticated user contacts',
            'city_id' => 1,
            'type_id' => 1,
        ]);
    }

    public function test_banned_user_cant_store() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 3]));

        $response = $this->withHeader('accept', 'application/json')->post('/api/v1/bills',
            [
                'title'=>'test bill`s from banned user title',
                'details' => 'test bill`s from banned user detail',
                'contacts' => 'banned user contacts',
                'city_id' => 1,
                'type_id' => 1,
            ]
        );

        $response->assertStatus(403);

        $this->assertDatabaseMissing('bills', [
            'title'=>'test bill`s from banned user title',
            'details' => 'test bill`s from banned user detail',
            'contacts' => 'banned user contacts',
            'city_id' => 1,
            'type_id' => 1,
        ]);
    }

    public function test_bill_with_invalid_data_doesnt_store() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')->post('/api/v1/bills',
            [
                'title'=>'test bill`s with invalid title',
                'details' => 'test bill`s with invalid  detail',
                'city_id' => 21,
                'type_id' => 1,
            ]
        );

        $response->assertStatus(422);

        $this->assertDatabaseMissing('bills', [
            'title'=>'test bill`s with invalid title',
            'details' => 'test bill`s with invalid  detail',
            'city_id' => 21,
            'type_id' => 1,
        ]);
    }

    public function test_bill_with_valid_data_stores() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')->post('/api/v1/bills',
            [
                'title'=>'test bill`s with valid title',
                'details' => 'test bill`s with valid  detail',
                'contacts' => 'user contacts',
                'city_id' => 1,
                'type_id' => 1,
            ]
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas('bills', [
            'title'=>'test bill`s with valid title',
            'details' => 'test bill`s with valid  detail',
            'contacts' => 'user contacts',
            'city_id' => 1,
            'type_id' => 1,
        ]);
    }
}
