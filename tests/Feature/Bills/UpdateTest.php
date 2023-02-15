<?php

namespace Tests\Feature\Bills;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_update_bill()
    {
        $bill = Bill::all()->first;

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/bills/{$bill->id}", [
            'title' => 'this title wont apply',
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('bills', [
            'id' => $bill->id,
            'title' => 'this title wont apply',
        ]);
    }

    public function test_unauthorized_user_cant_update_bill()
    {
        $bill = Bill::all()->first();

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/bills/{$bill->id}", [
                'title' => 'this title wont apply',
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('bills', [
            'id' => $bill->id,
            'title' => 'this title wont apply',
        ]);
    }

    public function test_bill_doesnt_update_with_invalid_data() {
        $bill = Bill::all()->first();

        Sanctum::actingAs(User::where('id', $bill->user_id)->first());

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/bills/{$bill->id}", [
                'city_id' => 22,
            ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('bills', [
            'id' => $bill->id,
            'city_id' => 22,
        ]);
    }

    public function test_author_can_patch_own_bill() {
        $bill = Bill::all()->first();

        Sanctum::actingAs(User::where('id', $bill->user_id)->first());

        $response = $this->withHeader('accept', 'application/json')
            ->patch("/api/v1/bills/{$bill->id}", [
                'title' => 'this title will apply',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'title' => 'this title will apply',
        ]);
    }
}
