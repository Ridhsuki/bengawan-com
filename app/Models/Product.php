<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'price',
        'discount_price',
        'stock',
        'link_shopee',
        'link_tokopedia',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount)
            return 0;

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute(): string
    {
        return 'Rp' . number_format((float) $this->discount_price, 0, ',', '.');
    }

    public function getWhatsappInquiryLinkAttribute(): string
    {
        $settings = Setting::getData();
        $priceDisplay = $this->has_discount
            ? $this->formatted_discount_price
            : $this->formatted_price;

        $productUrl = route('products.show', $this->slug);

        $message = "Halo Bengawan Computer, saya tertarik dengan produk ini:\n\n";
        $message .= "*{$this->name}*\n";
        $message .= "Harga: {$priceDisplay}\n";
        $message .= "Link: {$productUrl}\n\n";
        $message .= "Apakah stoknya masih tersedia?";

        return $settings->getWhatsappUrl($message);
    }
}
