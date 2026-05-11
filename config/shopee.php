<?php

return [
    'host' => env('SHOPEE_HOST', 'https://openplatform.sandbox.test-stable.shopee.sg'),

    'auth_host' => env(
        'SHOPEE_AUTH_HOST',
        env('SHOPEE_HOST', 'https://openplatform.sandbox.test-stable.shopee.sg')
    ),

    'api_host' => env(
        'SHOPEE_API_HOST',
        env('SHOPEE_HOST', 'https://openplatform.sandbox.test-stable.shopee.sg')
    ),

    'partner_id' => (int) env('SHOPEE_PARTNER_ID'),
    'partner_key' => env('SHOPEE_PARTNER_KEY'),

    'redirect_url' => env('SHOPEE_REDIRECT_URL', env('APP_URL') . '/shopee/callback'),

    'webhook_verify' => (bool) env('SHOPEE_WEBHOOK_VERIFY', true),

    'timeout' => 20,
];
