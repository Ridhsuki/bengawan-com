<?php

namespace App\Services\Shopee;

use App\Models\ShopeeShop;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ShopeeClient
{
    private string $host;
    private int $partnerId;
    private string $partnerKey;

    public function __construct()
    {
        $this->host = rtrim((string) config('shopee.host'), '/');
        $this->partnerId = (int) config('shopee.partner_id');
        $this->partnerKey = (string) config('shopee.partner_key');
    }

    public function authUrl(): string
    {
        $path = '/api/v2/shop/auth_partner';
        $timestamp = time();

        $query = [
            'partner_id' => $this->partnerId,
            'timestamp' => $timestamp,
            'sign' => $this->publicSign($path, $timestamp),
            'redirect' => config('shopee.redirect_url'),
        ];

        return $this->host . $path . '?' . http_build_query($query);
    }

    public function getAccessToken(string $code, int $shopId): array
    {
        $path = '/api/v2/auth/token/get';

        return $this->publicPost($path, [
            'code' => $code,
            'shop_id' => $shopId,
            'partner_id' => $this->partnerId,
        ]);
    }

    public function refreshAccessToken(ShopeeShop $shop): ShopeeShop
    {
        $path = '/api/v2/auth/access_token/get';

        $response = $this->publicPost($path, [
            'refresh_token' => $shop->refresh_token,
            'shop_id' => (int) $shop->shop_id,
            'partner_id' => $this->partnerId,
        ]);

        $shop->forceFill([
            'access_token' => $response['access_token'] ?? null,
            'refresh_token' => $response['refresh_token'] ?? null,
            'token_expires_at' => now()->addSeconds((int) ($response['expire_in'] ?? 14400)),
            'is_active' => true,
        ])->save();

        return $shop->refresh();
    }

    public function updateStock(ShopeeShop $shop, int $itemId, int $modelId, int $stock): array
    {
        $body = [
            'item_id' => $itemId,
            'stock_list' => [
                [
                    'model_id' => $modelId,
                    'seller_stock' => [
                        [
                            'stock' => max(0, $stock),
                        ],
                    ],
                ],
            ],
        ];

        return $this->shopPost('/api/v2/product/update_stock', $shop, $body);
    }

    public function getOrderDetail(ShopeeShop $shop, array $orderSnList): array
    {
        return $this->shopGet('/api/v2/order/get_order_detail', $shop, [
            'order_sn_list' => implode(',', $orderSnList),
            'response_optional_fields' => implode(',', [
                'buyer_user_id',
                'buyer_username',
                'recipient_address',
                'item_list',
                'total_amount',
                'order_status',
                'pay_time',
                'update_time',
            ]),
        ]);
    }

    public function getOrderList(
        ShopeeShop $shop,
        int $timeFrom,
        int $timeTo,
        ?string $cursor = null
    ): array {
        return $this->shopGet('/api/v2/order/get_order_list', $shop, array_filter([
            'time_range_field' => 'update_time',
            'time_from' => $timeFrom,
            'time_to' => $timeTo,
            'page_size' => 50,
            'cursor' => $cursor,
        ], fn($value) => filled($value)));
    }

    public function getItemBaseInfo(ShopeeShop $shop, array $itemIds): array
    {
        return $this->shopGet('/api/v2/product/get_item_base_info', $shop, [
            'item_id_list' => implode(',', $itemIds),
        ]);
    }

    private function publicPost(string $path, array $body): array
    {
        $timestamp = time();

        $query = [
            'partner_id' => $this->partnerId,
            'timestamp' => $timestamp,
            'sign' => $this->publicSign($path, $timestamp),
        ];

        $response = $this->request()
            ->post($this->host . $path . '?' . http_build_query($query), $body)
            ->throw()
            ->json();

        return $this->validateResponse($response);
    }

    private function shopGet(string $path, ShopeeShop $shop, array $query = []): array
    {
        $shop = $this->ensureValidToken($shop);

        $timestamp = time();

        $query = array_merge($query, [
            'partner_id' => $this->partnerId,
            'timestamp' => $timestamp,
            'access_token' => $shop->access_token,
            'shop_id' => (int) $shop->shop_id,
            'sign' => $this->shopSign($path, $timestamp, $shop),
        ]);

        $response = $this->request()
            ->get($this->host . $path, $query)
            ->throw()
            ->json();

        return $this->validateResponse($response);
    }

    private function shopPost(string $path, ShopeeShop $shop, array $body): array
    {
        $shop = $this->ensureValidToken($shop);

        $timestamp = time();

        $query = [
            'partner_id' => $this->partnerId,
            'timestamp' => $timestamp,
            'access_token' => $shop->access_token,
            'shop_id' => (int) $shop->shop_id,
            'sign' => $this->shopSign($path, $timestamp, $shop),
        ];

        $response = $this->request()
            ->post($this->host . $path . '?' . http_build_query($query), $body)
            ->throw()
            ->json();

        return $this->validateResponse($response);
    }

    private function ensureValidToken(ShopeeShop $shop): ShopeeShop
    {
        if ($shop->tokenWillExpireSoon()) {
            return $this->refreshAccessToken($shop);
        }

        return $shop;
    }

    private function publicSign(string $path, int $timestamp): string
    {
        return hash_hmac(
            'sha256',
            $this->partnerId . $path . $timestamp,
            $this->partnerKey
        );
    }

    private function shopSign(string $path, int $timestamp, ShopeeShop $shop): string
    {
        return hash_hmac(
            'sha256',
            $this->partnerId . $path . $timestamp . $shop->access_token . $shop->shop_id,
            $this->partnerKey
        );
    }

    private function request(): PendingRequest
    {
        return Http::timeout((int) config('shopee.timeout', 20))
            ->acceptJson()
            ->asJson();
    }

    private function validateResponse(array $response): array
    {
        if (filled($response['error'] ?? null)) {
            throw new RuntimeException(json_encode([
                'error' => $response['error'] ?? 'Shopee API error',
                'message' => $response['message'] ?? '-',
                'request_id' => $response['request_id'] ?? null,
            ]));
        }

        return $response;
    }
}
