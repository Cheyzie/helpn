<?php

namespace Tests\Feature\Bills;

use App\Models\Bill;
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

    public function test_unauthenticated_user_cant_get_bills() {
        $this->withHeader('accept', 'application/json')
            ->get('/api/v1/bills')
            ->assertStatus(401);
    }

    public function test_banned_user_cant_get_bills() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 3]));

        $this->withHeader('accept', 'application/json')
            ->get('/api/v1/bills')->assertStatus(403);
    }

    public function test_can_get_all_bills() {
        Sanctum::actingAs(User::factory()->create([
            'role_id'=> 2,
        ]));

        $firstBill = Bill::all()->first();

        $this->withHeader('accept', 'application/json')
            ->get('/api/v1/bills')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('bills', Bill::all()->count(), fn ($json) =>
                    $json->where('id', $firstBill->id)
                        ->where('title', $firstBill->title)
                        ->where('details', $firstBill->details)
                        ->has('user')
                        ->has('city')
                        ->has('type')
                        ->etc()
                )
            );
    }

}
