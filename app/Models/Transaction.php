<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_id',
        'employee_id',
        'division',
        'asset_code_snapshot',
        'asset_category_snapshot',
        'asset_location_snapshot',
        'condition_note',
        'inspection_confirmed',
        'borrowed_at',
        'returned_at',
        'cost',
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'returned_at' => 'date',
        'cost' => 'decimal:2',
        'inspection_confirmed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('returned_at');
    }
}
