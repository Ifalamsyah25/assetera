<?php

use App\Models\Asset;
use App\Models\Transaction;
use App\Models\User;

test('creating an active transaction marks asset as borrowed', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $borrower = User::factory()->create();
    $asset = Asset::factory()->create(['status_asset' => Asset::STATUS_AVAILABLE]);

    $response = $this->actingAs($staff)->post(route('transactions.store'), [
        'user_id' => $borrower->id,
        'asset_id' => $asset->id,
        'borrowed_at' => '2026-03-25',
        'returned_at' => null,
        'cost' => 5000,
    ]);

    $response->assertRedirect();

    expect($asset->fresh()->status_asset)->toBe(Asset::STATUS_BORROWED);
    $this->assertDatabaseHas('transactions', [
        'asset_id' => $asset->id,
        'user_id' => $borrower->id,
    ]);
});

test('damaged asset cannot be borrowed', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $borrower = User::factory()->create();
    $asset = Asset::factory()->create(['status_asset' => Asset::STATUS_DAMAGED]);

    $response = $this->actingAs($staff)
        ->from(route('transactions.create'))
        ->post(route('transactions.store'), [
            'user_id' => $borrower->id,
            'asset_id' => $asset->id,
            'borrowed_at' => '2026-03-25',
            'returned_at' => null,
            'cost' => 0,
        ]);

    $response->assertRedirect(route('transactions.create'));
    $response->assertSessionHasErrors('asset_id');
});

test('returning a transaction marks asset as available when there is no open maintenance', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
    $borrower = User::factory()->create();
    $asset = Asset::factory()->create(['status_asset' => Asset::STATUS_BORROWED]);
    $transaction = Transaction::factory()->for($borrower)->for($asset)->create([
        'borrowed_at' => '2026-03-20',
        'returned_at' => null,
    ]);

    $response = $this->actingAs($staff)->put(route('transactions.update', $transaction), [
        'user_id' => $borrower->id,
        'asset_id' => $asset->id,
        'borrowed_at' => '2026-03-20',
        'returned_at' => '2026-03-21',
        'cost' => 0,
    ]);

    $response->assertRedirect();
    expect($asset->fresh()->status_asset)->toBe(Asset::STATUS_AVAILABLE);
});
