<?php

namespace Tests\Feature\BillReport;

use App\Models\Bill;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_unauthenticated_user_cant_delete_report()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $bill = Bill::all()->first();

        $report = Report::create([
            'user_id' => $user->id,
            'bill_id' => $bill->id,
            'details' => 'Test report',
        ]);

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/reports/{$report->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('reports', ['id' => $report->id]);
    }

    public function test_unauthorized_user_cant_delete_report()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $bill = Bill::all()->first();

        $report = Report::create([
            'user_id' => $user->id,
            'bill_id' => $bill->id,
            'details' => 'Test report',
        ]);

        Sanctum::actingAs(User::factory()->create(['role_id' => 2]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/reports/{$report->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('reports', ['id' => $report->id]);
    }

    public function test_admin_can_delete_report()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $bill = Bill::all()->first();

        $report = Report::create([
            'user_id' => $user->id,
            'bill_id' => $bill->id,
            'details' => 'Test report',
        ]);

        Sanctum::actingAs(User::factory()->create(['role_id' => 1]));

        $response = $this->withHeader('accept', 'application/json')
            ->delete("/api/v1/reports/{$report->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
    }
}
