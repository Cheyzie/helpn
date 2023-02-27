<?php

namespace Tests\Feature\BillReport;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_create_report()
    {
        $bill = Bill::all()->first();

        $response = $this->withHeader('accept', 'application/json')
            ->post("/api/v1/bills/{$bill->id}/reports", ['details' => 'Test created report']);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('reports', [
            'bill_id' => $bill->id,
            'details' => 'Test created report',
        ]);
    }

    public function test_banned_user_cant_create_report()
    {
        $bill = Bill::all()->first();

        $user = User::factory()->create(['role_id' => 3]);

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->post("/api/v1/bills/{$bill->id}/reports", ['details' => 'Test created report']);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('reports', [
            'bill_id' => $bill->id,
            'user_id' => $user->id,
            'details' => 'Test created report',
        ]);
    }

    public function test_authorized_user_can_create_report()
    {
        $bill = Bill::all()->first();

        $user = User::factory()->create(['role_id' => 2]);

        Sanctum::actingAs($user);

        $response = $this->withHeader('accept', 'application/json')
            ->post("/api/v1/bills/{$bill->id}/reports", ['details' => 'Test created report']);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reports', [
            'bill_id' => $bill->id,
            'user_id' => $user->id,
            'details' => 'Test created report',
        ]);
    }
}
