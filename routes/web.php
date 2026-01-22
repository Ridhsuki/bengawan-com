<?php

use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'service'])->name('service');

Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');

Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

// Diskon
Route::get('/discount', [HomeController::class, 'discount'])->name('discount');

Route::post('/feedback', [FeedbackController::class, 'store'])
    ->name('feedback.store');
