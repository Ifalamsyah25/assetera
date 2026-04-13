<?php

use App\Models\User;

test('staff cannot access user management routes', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

    $this->actingAs($staff)
        ->get(route('users.index'))
        ->assertForbidden();
});

test('pimpinan can access report endpoints', function () {
    $leader = User::factory()->create(['role' => User::ROLE_PIMPINAN]);

    $this->actingAs($leader)
        ->get(route('reports.summary'))
        ->assertOk();
});

test('pimpinan cannot create transactions', function () {
    $leader = User::factory()->create(['role' => User::ROLE_PIMPINAN]);

    $this->actingAs($leader)
        ->get(route('transactions.create'))
        ->assertForbidden();
});

test('staff cannot access summary report endpoint', function () {
    $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

    $this->actingAs($staff)
        ->get(route('reports.summary'))
        ->assertForbidden();
});
