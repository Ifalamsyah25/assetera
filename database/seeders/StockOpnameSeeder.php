<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\StockOpname;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StockOpnameSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure users exist
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Assetera',
                'email' => 'admin@assetera.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Gudang',
                'email' => 'staff@assetera.com',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Clear old assets, asset requests, and stock opnames for a clean test
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        StockOpname::truncate();
        AssetRequest::truncate();
        Asset::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 3. Seed specific assets from the mockup
        $specificAssets = [
            [
                'code_asset' => 'AST-2025-215',
                'name_asset' => 'Mixer',
                'category_asset' => 'Peralatan',
                'merk_asset' => 'Phillips',
                'lokasi_asset' => 'SPPG Garuda',
                'kondisi_asset' => 'Baik',
                'status_asset' => 'available',
                'purchase_date' => '2025-06-15',
                'purchase_price' => 1500000,
                'deskripsi_asset' => 'Mixer Dapur SPPG Garuda',
            ],
            [
                'code_asset' => 'AST-2025-004',
                'name_asset' => 'Mixer',
                'category_asset' => 'Peralatan',
                'merk_asset' => 'Phillips',
                'lokasi_asset' => 'SPPG Garuda',
                'kondisi_asset' => 'Baik',
                'status_asset' => 'available',
                'purchase_date' => '2025-06-15',
                'purchase_price' => 1500000,
                'deskripsi_asset' => 'Mixer Dapur SPPG Garuda',
            ],
            [
                'code_asset' => 'AST-2025-015',
                'name_asset' => 'Mixer',
                'category_asset' => 'Peralatan',
                'merk_asset' => 'Phillips',
                'lokasi_asset' => 'SPPG Garuda',
                'kondisi_asset' => 'Baik',
                'status_asset' => 'available',
                'purchase_date' => '2025-06-15',
                'purchase_price' => 1500000,
                'deskripsi_asset' => 'Mixer Dapur SPPG Garuda',
            ],
            [
                'code_asset' => 'AST-2025-088',
                'name_asset' => 'Mixer',
                'category_asset' => 'Peralatan',
                'merk_asset' => 'Phillips',
                'lokasi_asset' => 'SPPG Garuda',
                'kondisi_asset' => 'Baik',
                'status_asset' => 'available',
                'purchase_date' => '2025-06-15',
                'purchase_price' => 1500000,
                'deskripsi_asset' => 'Mixer Dapur SPPG Garuda',
            ],
        ];

        $insertedSpecific = [];
        foreach ($specificAssets as $sa) {
            $insertedSpecific[] = Asset::create($sa);
        }

        // 4. Seed assets purchased this month (June 2026) totaling Rp18,000,000
        // Let's seed 6 assets costing 3,000,000 each = 18,000,000
        $june2026Assets = [];
        for ($i = 1; $i <= 6; $i++) {
            $june2026Assets[] = Asset::create([
                'code_asset' => "AST-2026-00$i",
                'name_asset' => "Blender Philip T-$i",
                'category_asset' => 'Peralatan',
                'merk_asset' => 'Phillips',
                'lokasi_asset' => 'SPPG Garuda',
                'kondisi_asset' => 'Baik',
                'status_asset' => 'available',
                'purchase_date' => "2026-06-10",
                'purchase_price' => 3000000,
                'deskripsi_asset' => 'Pengadaan Blender Baru',
            ]);
        }

        // 5. Seed other random assets to reach exactly 140 total assets
        $totalTargetAssets = 140;
        $currentAssetCount = Asset::count();
        $neededAssets = $totalTargetAssets - $currentAssetCount;

        $categories = ['Elektronik', 'Peralatan', 'Furnitur', 'Kendaraan'];
        $locations = ['SPPG Garuda', 'SPPG Merpati', 'SPPG Rajawali', 'Gudang Pusat'];

        for ($i = 1; $i <= $neededAssets; $i++) {
            Asset::create([
                'code_asset' => 'AST-RAND-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name_asset' => fake()->randomElement(['Kulkas Showcase', 'Rice Cooker Jumbo', 'Kompor Gas Rinnai', 'Meja Stainless', 'Kursi Plastik']),
                'category_asset' => fake()->randomElement($categories),
                'merk_asset' => fake()->randomElement(['Sharp', 'Rinnai', 'Miyako', 'Panasonic']),
                'lokasi_asset' => fake()->randomElement($locations),
                'kondisi_asset' => fake()->randomElement(['Baik', 'Rusak Ringan', 'Rusak Berat']),
                'status_asset' => fake()->randomElement(['available', 'borrowed', 'damaged']),
                'purchase_date' => fake()->date('Y-m-d', '2026-05-30'),
                'purchase_price' => fake()->numberBetween(200000, 2500000),
                'deskripsi_asset' => 'Aset operasional dapur',
            ]);
        }

        // 6. Seed 13 pending AssetRequests
        for ($i = 1; $i <= 13; $i++) {
            AssetRequest::create([
                'user_id' => $staff->id,
                'item_name' => fake()->randomElement(['Timbangan Digital', 'Pisau Set Professional', 'Wajan Maxim', 'Panci Presto']),
                'quantity' => fake()->numberBetween(1, 5),
                'category' => 'Peralatan',
                'estimated_cost' => fake()->numberBetween(300000, 1500000),
                'reason' => 'Untuk mendukung operasional Dapur MBG',
                'status' => 'pending',
            ]);
        }

        // 7. Seed Stock Opnames for the period "Juni 2026"
        // Target: 100 audited assets in total
        // We will seed:
        // - Specific mockup assets:
        //   AST-2025-215 (Sistem: 1, Aktual: 0, Selisih: 1, Status: Selisih)
        //   AST-2025-004 (Sistem: 1, Aktual: 1, Selisih: 0, Status: Sesuai)
        //   AST-2025-015 (Sistem: 4, Aktual: 2, Selisih: 2, Status: Selisih)
        //   AST-2025-088 (Sistem: 1, Aktual: 1, Selisih: 0, Status: Sesuai)
        // - 6 new June assets (all Sesuai)
        // - 8 other Selisih records (making total Selisih = 10)
        // - 82 other Sesuai records (making total Audited = 100)

        // Seed specific ones
        StockOpname::create([
            'asset_id' => $insertedSpecific[0]->id, // AST-2025-215
            'qty_sistem' => 1,
            'qty_aktual' => 0,
            'selisih' => 1,
            'status' => 'selisih',
            'periode' => 'Juni 2026',
            'tanggal_audit' => '2026-06-25',
            'auditor_id' => $admin->id,
            'keterangan' => 'Barang tidak ditemukan di lokasi',
        ]);

        StockOpname::create([
            'asset_id' => $insertedSpecific[1]->id, // AST-2025-004
            'qty_sistem' => 1,
            'qty_aktual' => 1,
            'selisih' => 0,
            'status' => 'sesuai',
            'periode' => 'Juni 2026',
            'tanggal_audit' => '2026-06-25',
            'auditor_id' => $admin->id,
            'keterangan' => 'Kondisi barang sesuai dan lengkap',
        ]);

        StockOpname::create([
            'asset_id' => $insertedSpecific[2]->id, // AST-2025-015
            'qty_sistem' => 4,
            'qty_aktual' => 2,
            'selisih' => 2,
            'status' => 'selisih',
            'periode' => 'Juni 2026',
            'tanggal_audit' => '2026-06-25',
            'auditor_id' => $admin->id,
            'keterangan' => '2 unit rusak berat dan disimpan di gudang pusat',
        ]);

        StockOpname::create([
            'asset_id' => $insertedSpecific[3]->id, // AST-2025-088
            'qty_sistem' => 1,
            'qty_aktual' => 1,
            'selisih' => 0,
            'status' => 'sesuai',
            'periode' => 'Juni 2026',
            'tanggal_audit' => '2026-06-25',
            'auditor_id' => $admin->id,
            'keterangan' => 'Barang terawat dengan baik',
        ]);

        // Seed 6 new June assets as Sesuai
        foreach ($june2026Assets as $asset) {
            StockOpname::create([
                'asset_id' => $asset->id,
                'qty_sistem' => 1,
                'qty_aktual' => 1,
                'selisih' => 0,
                'status' => 'sesuai',
                'periode' => 'Juni 2026',
                'tanggal_audit' => '2026-06-25',
                'auditor_id' => $admin->id,
                'keterangan' => 'Peralatan baru datang',
            ]);
        }

        // We need 8 other Selisih records
        // Get random assets that are not the specific ones or the new June ones
        $excludedIds = array_merge(
            array_column($insertedSpecific, 'id'),
            array_column($june2026Assets, 'id')
        );

        $otherAssets = Asset::whereNotIn('id', $excludedIds)->get();

        for ($i = 0; $i < 8; $i++) {
            $asset = $otherAssets[$i];
            $qtySistem = fake()->numberBetween(2, 6);
            $qtyAktual = $qtySistem - fake()->numberBetween(1, 2);
            StockOpname::create([
                'asset_id' => $asset->id,
                'qty_sistem' => $qtySistem,
                'qty_aktual' => $qtyAktual,
                'selisih' => abs($qtySistem - $qtyAktual),
                'status' => 'selisih',
                'periode' => 'Juni 2026',
                'tanggal_audit' => '2026-06-26',
                'auditor_id' => $admin->id,
                'keterangan' => 'Selisih kuantitas fisik',
            ]);
        }

        // We need 82 other Sesuai records to make it exactly 100 audited assets in total
        for ($i = 8; $i < 90; $i++) {
            $asset = $otherAssets[$i];
            $qtySistem = fake()->numberBetween(1, 3);
            StockOpname::create([
                'asset_id' => $asset->id,
                'qty_sistem' => $qtySistem,
                'qty_aktual' => $qtySistem,
                'selisih' => 0,
                'status' => 'sesuai',
                'periode' => 'Juni 2026',
                'tanggal_audit' => '2026-06-26',
                'auditor_id' => $admin->id,
                'keterangan' => 'Sesuai',
            ]);
        }
    }
}
