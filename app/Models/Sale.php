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
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'negotiated_price' => 'decimal:2',
        'total_profit' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
