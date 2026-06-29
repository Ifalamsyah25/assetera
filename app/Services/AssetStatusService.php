<?php

namespace App\Services;

use App\Models\Asset;

class AssetStatusService
{
    /**
     * Sinkronisasi status aset secara otomatis berdasarkan relasi
     */
    public function sync(Asset $asset): Asset
    {
        $asset->refresh();

        // 1. Jika ada maintenance aktif, set status rusak
        if ($asset->maintenances()->open()->exists()) {
            $asset->update(['status_asset' => Asset::STATUS_DAMAGED]);
            return $asset;
        }

        // 2. Jika ada transaksi aktif, set status dipinjam
        if ($asset->transactions()->active()->exists()) {
            $asset->update(['status_asset' => Asset::STATUS_BORROWED]);
            return $asset;
        }

        // 3. Jika aman, set status tersedia
        $asset->update(['status_asset' => Asset::STATUS_AVAILABLE]);
        return $asset;
    }
}