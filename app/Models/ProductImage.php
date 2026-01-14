<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::deleted(function ($productImage) {
            if ($productImage->image && Storage::disk('public')->exists($productImage->image)) {
                Storage::disk('public')->delete($productImage->image);
            }
        });

        static::updating(function ($productImage) {
            if ($productImage->isDirty('image')) {
                $oldImage = $productImage->getOriginal('image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });
    }
}
