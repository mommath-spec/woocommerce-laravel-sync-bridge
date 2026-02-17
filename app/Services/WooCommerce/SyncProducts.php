<?php

namespace App\Services\WooCommerce;

use App\Models\Product;
use App\Models\SyncLog;
use Illuminate\Support\Facades\Log;

class SyncProducts
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
            $items = $this->client->getProducts($page, $perPage);
            $batchCount = count($items);
            $fetched += $batchCount;

            foreach ($items as $item) {
                Product::updateOrCreate(
                    ['woo_id' => (int) ($item['id'] ?? 0)],
                    [
                        'name' => (string) ($item['name'] ?? ''),
                        'sku' => (string) ($item['sku'] ?? ''),
                        'price' => isset($item['price']) ? (float) $item['price'] : null,
                        'status' => (string) ($item['status'] ?? ''),
                        'raw_payload' => $item,
                    ]
                );
                $upserted++;
            }

            $page++;
        } while ($batchCount >= $perPage);

        $duration = (int) round((microtime(true) - $startedAt) * 1000);

        $log = SyncLog::create([
            'type' => 'products',
            'fetched_count' => $fetched,
            'upserted_count' => $upserted,
            'duration_ms' => $duration,
            'status' => 'ok',
            'message' => 'Products sync completed',
            'context' => ['per_page' => $perPage],
        ]);

        Log::channel(config('woocommerce.log_channel', 'stack'))->info('Woo products sync finished', [
            'fetched' => $fetched,
            'upserted' => $upserted,
            'duration_ms' => $duration,
            'sync_log_id' => $log->id,
        ]);

        return compact('fetched', 'upserted', 'duration');
    }
}
