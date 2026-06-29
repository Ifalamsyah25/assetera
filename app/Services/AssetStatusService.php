<?php

namespace App\Services;

use App\Models\Asset;

class AssetStatusService
{
    public function sync(Asset $asset): Asset
    {
        // Ambil status baru berdasarkan kondisi transaksi/maintenance
        if ($asset->maintenances()->whereIn('status', ['pending', 'in_progress'])->exists()) {
            $status = Asset::STATUS_DAMAGED;
        } elseif ($asset->transactions()->whereNull('returned_at')->exists()) {
            $status = Asset::STATUS_BORROWED;
        } else {
            $status = Asset::STATUS_AVAILABLE;
        }

        // Update ke database
        $asset->update([
            'status_asset' => $status,
        ]);

        return $asset;
    }
}