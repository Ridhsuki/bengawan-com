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
        'product_name_snapshot',
        'product_sku_snapshot',
        'product_shopee_item_id_snapshot',
        'product_shopee_model_id_snapshot',
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
        'product_shopee_item_id_snapshot' => 'integer',
        'product_shopee_model_id_snapshot' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function getDisplayProductNameAttribute(): string
    {
        return $this->product_name_snapshot
            ?: $this->product?->name
            ?: 'Produk sudah dihapus';
    }
}
