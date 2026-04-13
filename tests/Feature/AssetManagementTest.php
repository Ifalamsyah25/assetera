<?php

use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Transaction;
use App\Models\User;

test('admin can create an asset', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $response = $this->actingAs($admin)->post(route('assets.store'), [
        'code_asset' => 'AST-001',
        'name_asset' => 'Kompor Stainless',
        'category_asset' => 'Peralatan',
        'status_asset' => Asset::STATUS_AVAILABLE,
        'purchase_date' => '2026-03-30',
        'purchase_price' => 1500000,
    ]);

    $response->assertRedirect(route('assets.index'));

    $this->assertDatabaseHas('assets', [
        'code_asset' => 'AST-001',
        'name_asset' => 'Kompor Stainless',
        'status_asset' => Asset::STATUS_AVAILABLE,
    ]);
});

test('asset creation validates allowed status', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

    $response = $this->actingAs($staff)->from(route('assets.create'))->post(route('assets.store'), [
        'code_asset' => 'AST-002',
        'name_asset' => 'Meja',
        'category_asset' => 'Furnitur',
        'status_asset' => 'hilang',
        'purchase_date' => '2026-03-30',
        'purchase_price' => 400000,
    ]);

    $response->assertRedirect(route('assets.create'));
    $response->assertSessionHasErrors('status_asset');
});

test('staff can delete asset without history', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $asset = Asset::factory()->create();

    $response = $this->actingAs($staff)->delete(route('assets.destroy', $asset));

    $response->assertRedirect(route('assets.index'));
    $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    $this->assertDatabaseHas('audit_logs', [
        'action' => 'asset.deleted',
        'user_id' => $staff->id,
    ]);
});

test('asset with transaction history cannot be deleted', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $borrower = User::factory()->create();
    $asset = Asset::factory()->create();

    Transaction::factory()->for($borrower)->for($asset)->create();

    $response = $this->actingAs($admin)->delete(route('assets.destroy', $asset));

    $response->assertRedirect(route('assets.index'));
    $response->assertSessionHasErrors('asset');
    $this->assertDatabaseHas('assets', ['id' => $asset->id]);
});

test('staff can update asset data', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $asset = Asset::factory()->create([
        'code_asset' => 'AST-OLD',
        'name_asset' => 'Kompor Lama',
    ]);

    $response = $this->actingAs($staff)->put(route('assets.update', $asset), [
        'code_asset' => 'AST-NEW',
        'name_asset' => 'Kompor Baru',
        'category_asset' => 'Peralatan',
        'status_asset' => Asset::STATUS_AVAILABLE,
        'purchase_date' => '2026-03-30',
        'purchase_price' => 2000000,
    ]);

    $response->assertRedirect(route('assets.index'));
    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'code_asset' => 'AST-NEW',
        'name_asset' => 'Kompor Baru',
    ]);
    $this->assertDatabaseHas('audit_logs', [
        'action' => 'asset.updated',
        'user_id' => $staff->id,
    ]);
});

test('asset export returns csv response', function () {
    $leader = User::factory()->create(['role' => User::ROLE_PIMPINAN]);
    Asset::factory()->create(['code_asset' => 'AST-CSV']);

    $response = $this->actingAs($leader)->get(route('assets.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $this->assertDatabaseHas('audit_logs', [
        'action' => 'asset.exported',
        'user_id' => $leader->id,
    ]);
});
