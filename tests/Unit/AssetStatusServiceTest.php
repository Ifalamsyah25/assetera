<?php

use App\Models\Asset;
use App\Services\AssetStatusService;
use Illuminate\Support\Facades\Log;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->service = new AssetStatusService();
});

it('sets asset status to damaged when there is an open maintenance', function () {
    $asset = Mockery::mock(Asset::class);
    $asset->shouldReceive('refresh')->once()->andReturnSelf();
    $openMock = Mockery::mock();
    $openMock->shouldReceive('exists')->andReturn(true);
    $maintMock = Mockery::mock();
    $maintMock->shouldReceive('open')->andReturn($openMock);
    $asset->shouldReceive('maintenances')->andReturn($maintMock);
    $asset->shouldReceive('update')->with(['status_asset' => Asset::STATUS_DAMAGED])->once();

    $this->service->sync($asset);
});

it('sets asset status to borrowed when there is an active transaction and no open maintenance', function () {
    $asset = Mockery::mock(Asset::class);
    $asset->shouldReceive('refresh')->once()->andReturnSelf();
    $openMock = Mockery::mock();
    $openMock->shouldReceive('exists')->andReturn(false);
    $maintMock = Mockery::mock();
    $maintMock->shouldReceive('open')->andReturn($openMock);
    $asset->shouldReceive('maintenances')->andReturn($maintMock);

    $activeMock = Mockery::mock();
    $activeMock->shouldReceive('exists')->andReturn(true);
    $transMock = Mockery::mock();
    $transMock->shouldReceive('active')->andReturn($activeMock);
    $asset->shouldReceive('transactions')->andReturn($transMock);
    $asset->shouldReceive('update')->with(['status_asset' => Asset::STATUS_BORROWED])->once();

    $this->service->sync($asset);
});

it('sets asset status to available when there is no open maintenance and no active transaction', function () {
    $asset = Mockery::mock(Asset::class);
    $asset->shouldReceive('refresh')->once()->andReturnSelf();
    $openMock = Mockery::mock();
    $openMock->shouldReceive('exists')->andReturn(false);
    $maintMock = Mockery::mock();
    $maintMock->shouldReceive('open')->andReturn($openMock);
    $asset->shouldReceive('maintenances')->andReturn($maintMock);

    $activeMock = Mockery::mock();
    $activeMock->shouldReceive('exists')->andReturn(false);
    $transMock = Mockery::mock();
    $transMock->shouldReceive('active')->andReturn($activeMock);
    $asset->shouldReceive('transactions')->andReturn($transMock);
    $asset->shouldReceive('update')->with(['status_asset' => Asset::STATUS_AVAILABLE])->once();

    $this->service->sync($asset);
});
