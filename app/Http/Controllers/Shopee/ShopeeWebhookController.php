<?php

namespace App\Http\Controllers\Shopee;

use App\Http\Controllers\Controller;
use App\Jobs\SyncShopeeOrderJob;
use App\Models\ShopeeShop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopeeWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        \Log::info('Shopee webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->json()->all(),
        ]);

        if (config('shopee.webhook_verify') && !$this->hasValidSignature($request)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->json()->all();

        $code = (int) data_get($payload, 'code');
        $shopId = (int) data_get($payload, 'shop_id');
        $data = data_get($payload, 'data', []);

        $orderSn = data_get($data, 'order_sn')
            ?? data_get($data, 'ordersn')
            ?? data_get($payload, 'order_sn');

        $shop = ShopeeShop::where('shop_id', $shopId)->first();

        // Code 3 = Order status update push.
        if ($code === 3 && $shop && filled($orderSn)) {
            SyncShopeeOrderJob::dispatch($shop->id, (string) $orderSn);
        }

        return response()->json(['message' => 'OK']);
    }

    private function hasValidSignature(Request $request): bool
    {
        $signature = $request->header('Authorization')
            ?: $request->header('X-Shopee-Signature');

        if (blank($signature)) {
            return false;
        }

        $signature = Str::of($signature)
            ->replace('SHA256 ', '')
            ->trim()
            ->toString();

        $expected = hash_hmac(
            'sha256',
            $request->getContent(),
            (string) config('shopee.partner_key')
        );

        return hash_equals($expected, $signature);
    }
}
