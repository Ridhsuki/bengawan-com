<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopeeShop extends Model
{
    protected $fillable = [
        'shop_id',
        'shop_name',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_active',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'refresh_token' => 'encrypted',
            'token_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'shopee_shop_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ShopeeOrder::class);
    }

    public function tokenWillExpireSoon(): bool
    {
        return blank($this->token_expires_at)
            || $this->token_expires_at->lte(now()->addMinutes(30));
    }
}
