<?php

namespace App\Models;

use Database\Factories\AssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /** @use HasFactory<AssetFactory> */
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_DAMAGED = 'damaged';

    protected $fillable = [
        'code_asset',
        'name_asset',
        'category_asset',
        'merk_asset',
        'lokasi_asset',
        'kondisi_asset',
        'status_asset',
        'purchase_date',
        'purchase_price',
        'deskripsi_asset',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_AVAILABLE,
            self::STATUS_BORROWED,
            self::STATUS_DAMAGED,
        ];
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
