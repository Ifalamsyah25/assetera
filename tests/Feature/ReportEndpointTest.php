<?php

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\Transaction;
use App\Models\User;

test('summary report returns aggregate backend data', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $asset = Asset::factory()->create(['status_asset' => Asset::STATUS_AVAILABLE]);
    Transaction::factory()->for($admin)->for($asset)->returned()->create(['cost' => 12000]);
    Maintenance::factory()->for($asset)->create(['status' => Maintenance::STATUS_COMPLETED, 'cost' => 50000]);

    $response = $this->actingAs($admin)->getJson(route('reports.summary'));

    $response->assertOk()
        ->assertJsonStructure([
            'assets' => ['total', 'available', 'borrowed', 'damaged'],
            'transactions' => ['total', 'active', 'returned', 'total_cost'],
            'maintenances' => ['total', 'open', 'completed', 'total_cost'],
            'users' => ['total', 'admin', 'pimpinan', 'staff'],
        ]);
});

test('summary report page renders for browser requests', function () {
    $leader = User::factory()->create(['role' => User::ROLE_PIMPINAN]);

    $response = $this->actingAs($leader)->get(route('reports.summary'));

    $response->assertOk()
        ->assertSee('Asset Summary')
        ->assertSee('Ringkasan Statistik');
});

test('summary export returns downloadable csv', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $response = $this->actingAs($admin)->get(route('reports.summary.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $this->assertDatabaseHas('audit_logs', [
        'action' => 'report.summary.exported',
        'user_id' => $admin->id,
    ]);
});

test('audit logs report returns paginated data', function () {
    $leader = User::factory()->create(['role' => User::ROLE_PIMPINAN]);

    $response = $this->actingAs($leader)->get(route('reports.audit-logs'));

    $response->assertOk()
        ->assertJsonStructure(['current_page', 'data']);
});
