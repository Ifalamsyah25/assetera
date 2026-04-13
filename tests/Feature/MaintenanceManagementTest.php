<?php

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\User;

test('open maintenance marks asset as damaged', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $asset = Asset::factory()->create(['status_asset' => Asset::STATUS_AVAILABLE]);

    $response = $this->actingAs($staff)->post(route('maintenances.store'), [
        'asset_id' => $asset->id,
        'repair_description' => 'Perlu ganti komponen',
        'cost' => 100000,
        'status' => Maintenance::STATUS_PENDING,
    ]);

    $response->assertRedirect();
    expect($asset->fresh()->status_asset)->toBe(Asset::STATUS_DAMAGED);
});

test('completed maintenance restores asset availability when no loan is active', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $asset = Asset::factory()->create(['status_asset' => Asset::STATUS_DAMAGED]);
    $maintenance = Maintenance::factory()->for($asset)->create([
        'status' => Maintenance::STATUS_PENDING,
    ]);

    $response = $this->actingAs($staff)->put(route('maintenances.update', $maintenance), [
        'asset_id' => $asset->id,
        'repair_description' => 'Selesai diperbaiki',
        'cost' => 150000,
        'status' => Maintenance::STATUS_COMPLETED,
    ]);

    $response->assertRedirect();
    expect($asset->fresh()->status_asset)->toBe(Asset::STATUS_AVAILABLE);
});
