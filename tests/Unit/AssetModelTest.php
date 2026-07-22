<?php

use App\Models\Asset;

uses(Tests\TestCase::class);

it('generates an asset code with AST-YYYY-XXX format', function () {
    $code = Asset::generateCode();

    expect($code)->toMatch('/^AST-\d{4}-\d{3}$/');
    expect(substr($code, 4, 4))->toBe((string) date('Y'));
});
