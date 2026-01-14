<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Storage;

class Setting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'social_media' => 'array',
        'banners' => 'array',
    ];

    public static function getData()
    {
        return Cache::rememberForever('app_settings', function () {
            return self::first() ?? self::create([
                'company_name' => 'Bengawan Computer',
            ]);
        });
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('app_settings');
        });
        static::updating(function ($setting) {
            if ($setting->isDirty('banners')) {
                $oldBanners = $setting->getOriginal('banners') ?? [];
                $newBanners = $setting->banners ?? [];

                $oldImages = collect($oldBanners)->pluck('image')->filter()->all();
                $newImages = collect($newBanners)->pluck('image')->filter()->all();

                $deletedImages = array_diff($oldImages, $newImages);

                foreach ($deletedImages as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            Cache::forget('app_settings');
        });

        static::deleted(function ($setting) {
            $banners = $setting->banners ?? [];
            foreach ($banners as $banner) {
                if (isset($banner['image']) && Storage::disk('public')->exists($banner['image'])) {
                    Storage::disk('public')->delete($banner['image']);
                }
            }
            Cache::forget('app_settings');
        });
    }

    public function getWhatsappLinkAttribute(): string
    {
        return $this->getWhatsappUrl();
    }

    public function getWhatsappUrl(?string $message = null): string
    {
        $phone = $this->phone;

        if (empty($phone)) {
            return '#';
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $baseUrl = "https://wa.me/{$phone}";

        if ($message) {
            $encodedMessage = urlencode($message);
            return "{$baseUrl}?text={$encodedMessage}";
        }

        return $baseUrl;
    }

    public function getSocialMediaListAttribute()
    {
        $socials = $this->social_media;

        if (empty($socials)) {
            return [];
        }

        return collect($socials)->map(function ($item) {
            $url = $item['url'] ?? '';

            $path = parse_url($url, PHP_URL_PATH);
            $cleanPath = rtrim($path, '/');
            $usernameText = basename($cleanPath);
            $item['username'] = '@' . $usernameText;

            return $item;
        });
    }

    public function getBannerListAttribute()
    {
        $banners = $this->banners;

        if (empty($banners)) {
            return [];
        }

        return collect($banners)->map(function ($banner) {
            return [
                'image_url' => isset($banner['image']) ? asset('storage/' . $banner['image']) : null,
                'url' => $banner['url'] ?? null,
                'title' => $banner['title'] ?? null,
            ];
        });
    }
}
