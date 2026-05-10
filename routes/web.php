<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Shopee\ShopeeAuthController;
use App\Http\Controllers\Shopee\ShopeeWebhookController;


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
});

Route::get('/shopee/callback', [ShopeeAuthController::class, 'callback'])->name('shopee.callback');
Route::post('/shopee/webhook', ShopeeWebhookController::class)->name('shopee.webhook');
