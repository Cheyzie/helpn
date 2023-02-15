<?php

namespace Tests\Feature\Bills;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_get_bill() {
        $this->withHeader('accept', 'application/json')
            ->get('/api/v1/bills/1')->assertStatus(401);
    }

    public function test_banned_user_cant_get_bill() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 3]));

        $this->withHeader('accept', 'application/json')
            ->get('/api/v1/bills/1')->assertStatus(403);
    }

    public function test_can_get_existing_bill() {
        $bill = Bill::all()->first();
        $bill->makeVisible(['contacts']);

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $this->withHeader('accept', 'application/json')
            ->get("/api/v1/bills/{$bill->id}")
            ->assertOk()
            ->assertJson(['bill' => $bill->toArray()]);
    }

    public function test_cant_get_nonexisting_bill() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $this->withHeader('accept', 'application/json')
            ->get('/api/v1/bills/11')->assertStatus(404);
    }

}
