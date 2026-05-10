<?php

return [
    'host' => env('SHOPEE_HOST', 'https://partner.test-stable.shopeemobile.com'),

    'partner_id' => (int) env('SHOPEE_PARTNER_ID'),
    'partner_key' => env('SHOPEE_PARTNER_KEY'),

    'redirect_url' => env('SHOPEE_REDIRECT_URL', env('APP_URL') . '/shopee/callback'),

    'webhook_verify' => (bool) env('SHOPEE_WEBHOOK_VERIFY', true),

    'timeout' => 20,
];
