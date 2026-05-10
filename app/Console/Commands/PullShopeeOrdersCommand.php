<?php

namespace App\Console\Commands;

use App\Jobs\SyncShopeeOrderJob;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;

class PullShopeeOrdersCommand extends Command
{
    protected $signature = 'shopee:pull-orders {--hours=24}';

    protected $description = 'Pull Shopee orders periodically as webhook fallback.';

    public function handle(ShopeeClient $client): int
    {
        $timeTo = now()->timestamp;
        $timeFrom = now()->subHours((int) $this->option('hours'))->timestamp;

        ShopeeShop::where('is_active', true)->each(function (ShopeeShop $shop) use ($client, $timeFrom, $timeTo) {
            $cursor = null;

            do {
                $response = $client->getOrderList($shop, $timeFrom, $timeTo, $cursor);

                $orders = data_get($response, 'response.order_list', []);
                $cursor = data_get($response, 'response.next_cursor');
                $hasMore = (bool) data_get($response, 'response.more', false);

                foreach ($orders as $order) {
                    $orderSn = data_get($order, 'order_sn');

                    if (filled($orderSn)) {
                        SyncShopeeOrderJob::dispatch($shop->id, (string) $orderSn);
                    }
                }
            } while ($hasMore && filled($cursor));

            $shop->forceFill(['last_synced_at' => now()])->save();
        });

        return self::SUCCESS;
    }
}
