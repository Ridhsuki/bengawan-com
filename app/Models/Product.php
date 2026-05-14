<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Models\ShopeeShop;

/**
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $image
 *
 * @property string|null $serial_number
 * @property float $cost_price
 * @property float $price
 * @property float|null $discount_price
 * @property int|null $discount_percentage
 * @property int $stock
 * @property bool $is_active
 *
 * @property string|null $link_shopee
 * @property string|null $link_tokopedia
 *
 * @property int|null $shopee_shop_id
 * @property int|string|null $shopee_item_id
 * @property int|string|null $shopee_model_id
 * @property string|null $shopee_sku
 * @property int|string|null $shopee_category_id
 * @property int|string|null $shopee_brand_id
 * @property string|null $shopee_brand_name
 * @property string|null $shopee_condition
 * @property float|null $shopee_weight
 * @property float|null $shopee_package_length
 * @property float|null $shopee_package_width
 * @property float|null $shopee_package_height
 * @property int|string|null $shopee_logistic_id
 * @property bool $sync_shopee_stock
 * @property int|null $shopee_stock
 *
 * @property string|null $shopee_publish_status
 * @property string|null $shopee_item_status
 * @property string|null $shopee_sync_status
 * @property string|null $shopee_publish_error
 * @property string|null $shopee_sync_error
 * @property string|null $shopee_unlinked_reason
 * @property Carbon|null $shopee_last_synced_at
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ShopeeShop|null $shopeeShop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductImage[] $images
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;
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
        'serial_number',
        'cost_price',
        'shopee_shop_id',
        'shopee_item_id',
        'shopee_model_id',
        'shopee_sku',
        'sync_shopee_stock',
        'shopee_stock',
        'shopee_last_synced_at',
        'shopee_sync_status',
        'shopee_sync_error',
        'shopee_category_id',
        'shopee_brand_id',
        'shopee_brand_name',
        'shopee_condition',
        'shopee_weight',
        'shopee_package_length',
        'shopee_package_width',
        'shopee_package_height',
        'shopee_logistic_id',
        'shopee_publish_status',
        'shopee_publish_error',
        'shopee_published_at',
        'shopee_item_status',
        'shopee_deleted_at',
        'shopee_last_checked_at',
        'shopee_unlinked_reason',
        'shopee_last_item_id',
        'shopee_last_model_id',
        'shopee_last_sku',
        'shopee_last_shop_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'shopee_item_id' => 'integer',
        'shopee_model_id' => 'integer',
        'sync_shopee_stock' => 'boolean',
        'shopee_stock' => 'integer',
        'shopee_last_synced_at' => 'datetime',
        'shopee_category_id' => 'integer',
        'shopee_brand_id' => 'integer',
        'shopee_weight' => 'decimal:2',
        'shopee_package_length' => 'integer',
        'shopee_package_width' => 'integer',
        'shopee_package_height' => 'integer',
        'shopee_logistic_id' => 'integer',
        'shopee_published_at' => 'datetime',
        'shopee_deleted_at' => 'datetime',
        'shopee_last_checked_at' => 'datetime',
        'shopee_last_item_id' => 'integer',
        'shopee_last_model_id' => 'integer',
        'shopee_last_shop_id' => 'integer',
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

    public function shopeeShop(): BelongsTo
    {
        return $this->belongsTo(ShopeeShop::class, 'shopee_shop_id');
    }

    public function canSyncShopeeStock(): bool
    {
        return $this->sync_shopee_stock
            && filled($this->shopee_shop_id)
            && filled($this->shopee_item_id)
            && !in_array($this->shopee_item_status, ['deleted', 'not_found'], true)
            && !in_array($this->shopee_publish_status, ['deleted', 'not_found'], true);
    }

    public function isPublishedToShopee(): bool
    {
        return filled($this->shopee_item_id)
            && !in_array($this->shopee_item_status, ['deleted', 'not_found'], true)
            && !in_array($this->shopee_publish_status, ['deleted', 'not_found'], true);
    }

    public function hasActiveShopeeMapping(): bool
    {
        return filled($this->shopee_shop_id)
            && filled($this->shopee_item_id)
            && !in_array($this->shopee_item_status, ['deleted', 'not_found'], true);
    }

    public function canPublishToShopee(): bool
    {
        return filled($this->shopee_shop_id)
            && filled($this->shopee_category_id)
            && filled($this->shopee_weight)
            && filled($this->shopee_package_length)
            && filled($this->shopee_package_width)
            && filled($this->shopee_package_height)
            && filled($this->shopee_logistic_id)
            && filled($this->image)
            && filled($this->name)
            && filled($this->description)
            && filled($this->price);
    }

    protected static function booted(): void
    {
        static::updating(function (Product $product) {
            if (!$product->isDirty('image')) {
                return;
            }

            $oldImage = $product->getOriginal('image');
            $newImage = $product->image;

            if (
                filled($oldImage)
                && $oldImage !== $newImage
                && Storage::disk('public')->exists($oldImage)
            ) {
                Storage::disk('public')->delete($oldImage);
            }
        });

        static::deleting(function (Product $product) {
            if (!$product->isForceDeleting()) {
                return;
            }

            $product->images()->get()->each(function ($galleryItem) {
                $galleryItem->delete();
            });

            if (
                filled($product->image)
                && Storage::disk('public')->exists($product->image)
            ) {
                Storage::disk('public')->delete($product->image);
            }
        });
    }
}
