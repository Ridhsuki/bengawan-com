<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopeeOrder extends Model
{
    protected $fillable = [
        'shopee_shop_id',
        'order_sn',
        'order_status',
        'raw_payload',
        'stock_applied_at',
        'stock_restored_at',
    ];

    protected function casts(): array
    {
        return [
            'raw_payload' => 'array',
            'stock_applied_at' => 'datetime',
            'stock_restored_at' => 'datetime',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(ShopeeShop::class, 'shopee_shop_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShopeeOrderItem::class);
    }
}
