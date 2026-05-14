<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Shopee\ShopeeAuthController;
use App\Http\Controllers\Shopee\ShopeeWebhookController;

use App\Models\Product;
use App\Models\ShopeeShop;

Route::redirect('/login', '/admin/login')->name('login');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'service'])->name('service');

Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');

Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

Route::get('/discount', [HomeController::class, 'discount'])->name('discount');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/shopee/connect', [ShopeeAuthController::class, 'redirect'])->name('shopee.connect');

    Route::get('/debug/shopee-status', function () {
        abort_unless(app()->environment(['local', 'production']), 403);

        return response()->json([
            'app_url' => config('app.url'),
            'shopee' => [
                'host' => config('shopee.host'),
                'auth_host' => config('shopee.auth_host'),
                'api_host' => config('shopee.api_host'),
                'partner_id' => config('shopee.partner_id'),
                'redirect_url' => config('shopee.redirect_url'),
                'webhook_verify' => config('shopee.webhook_verify'),
            ],
            'shops_count' => ShopeeShop::count(),
            'shops' => ShopeeShop::select('id', 'shop_id', 'shop_name', 'is_active', 'token_expires_at')->get(),
            'mapped_products_count' => Product::whereNotNull('shopee_item_id')
                ->where('sync_shopee_stock', true)
                ->count(),
            'mapped_products' => Product::select(
                'id',
                'name',
                'stock',
                'shopee_shop_id',
                'shopee_item_id',
                'shopee_model_id',
                'sync_shopee_stock',
                'shopee_sync_status',
                'shopee_sync_error'
            )
                ->whereNotNull('shopee_item_id')
                ->limit(10)
                ->get(),
        ]);
    });
});

Route::get('/shopee/callback', [ShopeeAuthController::class, 'callback'])->name('shopee.callback');
Route::post('/shopee/webhook', ShopeeWebhookController::class)->name('shopee.webhook');
