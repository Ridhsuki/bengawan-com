<?php

namespace App\Console\Commands;

use App\Jobs\SyncShopeeOrderJob;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;
use Throwable;

class ShopeePullOrdersCommand extends Command
{
    protected $signature = 'shopee:pull-orders
        {--hours=24 : Rentang jam order yang ditarik berdasarkan update_time}
        {--status= : Optional order status, misalnya READY_TO_SHIP, SHIPPED, COMPLETED, CANCELLED}
        {--sync : Jalankan sync order langsung tanpa queue}';

    protected $description = 'Pull Shopee orders and dispatch sync jobs.';

    public function handle(ShopeeClient $client): int
    {
        $shops = ShopeeShop::where('is_active', true)->get();

        if ($shops->isEmpty()) {
            $this->error('Belum ada toko Shopee aktif.');
            return self::FAILURE;
        }

        $hours = max(1, (int) $this->option('hours'));
        $timeTo = now()->timestamp;
        $timeFrom = now()->subHours($hours)->timestamp;
        $orderStatus = $this->option('status') ?: null;
        $runSync = (bool) $this->option('sync');

        foreach ($shops as $shop) {
            $this->info("Pulling orders for Shopee shop {$shop->shop_id}...");

            $cursor = null;
            $total = 0;

            do {
                try {
                    $response = $client->getOrderList(
                        shop: $shop,
                        timeFrom: $timeFrom,
                        timeTo: $timeTo,
                        cursor: $cursor,
                        pageSize: 50,
                        timeRangeField: 'update_time',
                        orderStatus: $orderStatus,
                    );
                } catch (Throwable $e) {
                    $this->error("Gagal pull order shop {$shop->shop_id}: {$e->getMessage()}");
                    return self::FAILURE;
                }

                $orders = data_get($response, 'response.order_list', []);
                $cursor = data_get($response, 'response.next_cursor');
                $more = (bool) data_get($response, 'response.more', false);

                foreach ($orders as $order) {
                    $orderSn = data_get($order, 'order_sn');

                    if (blank($orderSn)) {
                        continue;
                    }

                    if ($runSync) {
                        SyncShopeeOrderJob::dispatchSync($shop->id, (string) $orderSn);
                    } else {
                        SyncShopeeOrderJob::dispatch($shop->id, (string) $orderSn);
                    }

                    $total++;
                    $this->line("Queued order: {$orderSn}");
                }
            } while ($more && filled($cursor));

            $this->info("Total orders queued for shop {$shop->shop_id}: {$total}");
        }

        return self::SUCCESS;
    }
}
