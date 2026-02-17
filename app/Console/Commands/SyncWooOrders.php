<?php

namespace App\Console\Commands;

use App\Jobs\SyncWooOrdersJob;
use App\Services\WooCommerce\SyncOrders;
use Illuminate\Console\Command;

class SyncWooOrders extends Command
{
    protected $signature = 'woo:sync-orders {--queued : Dispatch as queue job} {--per-page=50}';

    protected $description = 'Synchronize WooCommerce orders into local database';

    public function handle(SyncOrders $sync): int
    {
        $perPage = (int) $this->option('per-page');

        if ((bool) $this->option('queued')) {
            SyncWooOrdersJob::dispatch($perPage);
            $this->info('SyncWooOrdersJob dispatched.');

            return self::SUCCESS;
        }

        $result = $sync->run($perPage);
        $this->info("Orders synced: {$result['upserted']} records in {$result['duration']}ms");

        return self::SUCCESS;
    }
}
