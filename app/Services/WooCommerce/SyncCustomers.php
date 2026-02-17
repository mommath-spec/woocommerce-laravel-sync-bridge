<?php

namespace App\Services\WooCommerce;

use App\Models\Customer;
use App\Models\SyncLog;
use Illuminate\Support\Facades\Log;

class SyncCustomers
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
            $items = $this->client->getCustomers($page, $perPage);
            $batchCount = count($items);
            $fetched += $batchCount;

            foreach ($items as $item) {
                Customer::updateOrCreate(
                    ['woo_id' => (int) ($item['id'] ?? 0)],
                    [
                        'email' => (string) ($item['email'] ?? ''),
                        'first_name' => (string) ($item['first_name'] ?? ''),
                        'last_name' => (string) ($item['last_name'] ?? ''),
                        'raw_payload' => $item,
                    ]
                );
                $upserted++;
            }

            $page++;
        } while ($batchCount >= $perPage);

        $duration = (int) round((microtime(true) - $startedAt) * 1000);

        $log = SyncLog::create([
            'type' => 'customers',
            'fetched_count' => $fetched,
            'upserted_count' => $upserted,
            'duration_ms' => $duration,
            'status' => 'ok',
            'message' => 'Customers sync completed',
            'context' => ['per_page' => $perPage],
        ]);

        Log::channel(config('woocommerce.log_channel', 'stack'))->info('Woo customers sync finished', [
            'fetched' => $fetched,
            'upserted' => $upserted,
            'duration_ms' => $duration,
            'sync_log_id' => $log->id,
        ]);

        return compact('fetched', 'upserted', 'duration');
    }
}
