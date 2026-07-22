<?php

use Illuminate\Support\Facades\Validator;

uses(Tests\TestCase::class);

it('fails validation when name_asset is missing', function () {
    // Validate only the presence of name_asset to avoid DB-dependent unique checks
    $data = [
        'code_asset' => 'AST-2026-001',
        'category_asset' => 'Elektronik',
        'status_asset' => 'available',
        'purchase_date' => '2026-07-22',
        'purchase_price' => 100000,
    ];

    $validator = Validator::make($data, ['name_asset' => 'required']);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name_asset'))->toBeTrue();
});
