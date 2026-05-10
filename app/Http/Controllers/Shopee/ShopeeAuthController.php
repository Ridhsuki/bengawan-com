<?php

namespace App\Http\Controllers\Shopee;

use App\Http\Controllers\Controller;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShopeeAuthController extends Controller
{
    public function redirect(ShopeeClient $client): RedirectResponse
    {
        return redirect()->away($client->authUrl());
    }

    public function callback(Request $request, ShopeeClient $client): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'shop_id' => ['required', 'integer'],
        ]);

        $shopId = (int) $validated['shop_id'];

        $token = $client->getAccessToken(
            code: $validated['code'],
            shopId: $shopId
        );

        ShopeeShop::updateOrCreate(
            ['shop_id' => $shopId],
            [
                'shop_name' => 'Shopee Shop #' . $shopId,
                'access_token' => $token['access_token'] ?? null,
                'refresh_token' => $token['refresh_token'] ?? null,
                'token_expires_at' => now()->addSeconds((int) ($token['expire_in'] ?? 14400)),
                'is_active' => true,
            ]
        );

        return redirect('/admin')
            ->with('success', 'Toko Shopee berhasil dihubungkan.');
    }
}


