<?php

namespace Tests\Feature\Bills;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_delete_bill()
    {
        $bill = Bill::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/bills/{$bill->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('bills', ['id' => $bill->id]);
    }

    public function test_unauthorized_user_cant_delete_bill()
    {
        $bill = Bill::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/bills/{$bill->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('bills', ['id' => $bill->id]);
    }

    public function test_cant_delete_nonexist_bill() {
        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/bills/111");

        $response->assertStatus(404);
    }

    public function test_author_can_delete_bill() {
        $bill = Bill::all()->first();

        Sanctum::actingAs(User::where('id', $bill->user_id)->first());

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/bills/{$bill->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('bills', ['id' => $bill->id]);
    }


    public function test_admin_can_delete_any_bill() {
        $bill = Bill::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/bills/{$bill->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('bills', ['id' => $bill->id]);
    }
}
