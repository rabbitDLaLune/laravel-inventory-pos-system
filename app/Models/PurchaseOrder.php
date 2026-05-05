<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_id',
        'warehouse_id',
        'user_id',
        'po_number',
        'status',
        'total_amount',
        'ordered_at',
        'expected_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'ordered_at' => 'date',
        'expected_at' => 'date',
        'received_at' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
