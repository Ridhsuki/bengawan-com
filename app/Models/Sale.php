<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'cost_price',
        'selling_price',
        'negotiated_price',
        'total_profit',
        'customer_info',
        'transaction_date',
        'sales_channel',
        'external_order_sn',
        'external_item_id',
        'external_model_id',
        'external_status',
        'external_payload',
        'external_synced_at',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'negotiated_price' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'external_payload' => 'array',
        'external_synced_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
