<?php

use App\Models\Asset;
use App\Models\Maintenance;

uses(Tests\TestCase::class);

it('completes maintenance and updates the asset status to available', function () {
    $assetStub = new class {
        public $updated = [];
        public function update(array $attrs)
        {
            $this->updated = $attrs;
            return true;
        }
    };

    $maintenance = new class($assetStub) extends Maintenance {
        public function __construct($asset)
        {
            $this->setRelation('asset', $asset);
            $this->attributes = ['status' => Maintenance::STATUS_IN_PROGRESS];
        }

        public function update(array $attributes = [], array $options = [])
        {
            $this->attributes = array_merge($this->attributes ?? [], $attributes);
            return true;
        }
    };
    // call constructor manually for anonymous class instance
    $reflection = new ReflectionClass($maintenance);
    $constructor = $reflection->getConstructor();
    if ($constructor) {
        $constructor->invoke($maintenance, $assetStub);
    }

    $maintenance->complete();

    expect($maintenance->status)->toBe(Maintenance::STATUS_COMPLETED);
    expect($assetStub->updated['status_asset'])->toBe(Asset::STATUS_AVAILABLE);
});
