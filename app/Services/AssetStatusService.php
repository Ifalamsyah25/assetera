<?php

namespace App\Services;

use App\Models\Asset;

class AssetStatusService
{
    public function sync(Asset $asset): Asset
    {
        $asset->refresh();

        if ($asset->maintenances()->whereIn('status', ['pending', 'in_progress'])->exists()) {
            return tap($asset)->update([
                'status_asset' => Asset::STATUS_DAMAGED,
            ]);
        }

        if ($asset->transactions()->whereNull('returned_at')->exists()) {
            return tap($asset)->update([
                'status_asset' => Asset::STATUS_BORROWED,
            ]);
        }

        return tap($asset)->update([
            'status_asset' => Asset::STATUS_AVAILABLE,
        ]);
    }
}
