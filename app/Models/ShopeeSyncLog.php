<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopeeSyncLog extends Model
{
    protected $fillable = [
        'product_id',
        'shopee_shop_id',
        'type',
        'status',
        'message',
        'request_payload',
        'response_payload',
    ];

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
        ];
    }
}
