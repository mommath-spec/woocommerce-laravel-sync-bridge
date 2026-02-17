<?php

namespace App\Services\WooCommerce;

use App\Models\Order;
use App\Models\SyncLog;
use Illuminate\Support\Facades\Log;

class SyncOrders
{
    public function __construct(private readonly Client $client)
    {
    }

    public function run(int $perPage = 50): array
    {
        $startedAt = microtime(true);
        $page = 1;
        $fetched = 0;
        $upserted = 0;

        do {
            $items = $this->client->getOrders($page, $perPage);
            $batchCount = count($items);
            $fetched += $batchCount;

            foreach ($items as $item) {
                Order::updateOrCreate(
                    ['woo_id' => (int) ($item['id'] ?? 0)],
                    [
                        'status' => (string) ($item['status'] ?? ''),
                        'total' => isset($item['total']) ? (float) $item['total'] : null,
                        'currency' => (string) ($item['currency'] ?? ''),
                        'customer_woo_id' => isset($item['customer_id']) ? (int) $item['customer_id'] : null,
                        'raw_payload' => $item,
                    ]
                );
                $upserted++;
            }

            $page++;
        } while ($batchCount >= $perPage);

        $duration = (int) round((microtime(true) - $startedAt) * 1000);

        $log = SyncLog::create([
            'type' => 'orders',
            'fetched_count' => $fetched,
            'upserted_count' => $upserted,
            'duration_ms' => $duration,
            'status' => 'ok',
            'message' => 'Orders sync completed',
            'context' => ['per_page' => $perPage],
        ]);

        Log::channel(config('woocommerce.log_channel', 'stack'))->info('Woo orders sync finished', [
            'fetched' => $fetched,
            'upserted' => $upserted,
            'duration_ms' => $duration,
            'sync_log_id' => $log->id,
        ]);

        return compact('fetched', 'upserted', 'duration');
    }
}
