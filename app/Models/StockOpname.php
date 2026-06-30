<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpname extends Model
{
    use HasFactory;

    public const STATUS_SESUAI = 'sesuai';
    public const STATUS_SELISIH = 'selisih';

    protected $fillable = [
        'asset_id',
        'qty_sistem',
        'qty_aktual',
        'selisih',
        'status',
        'periode',
        'tanggal_audit',
        'auditor_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_audit' => 'date',
        'qty_sistem' => 'integer',
        'qty_aktual' => 'integer',
        'selisih' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}
