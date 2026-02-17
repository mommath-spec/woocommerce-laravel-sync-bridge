<?php

namespace App\Console\Commands;

use App\Jobs\SyncWooProductsJob;
use App\Services\WooCommerce\SyncProducts;
use Illuminate\Console\Command;

class SyncWooProducts extends Command
{
    protected $signature = 'woo:sync-products {--queued : Dispatch as queue job} {--per-page=50}';

    protected $description = 'Synchronize WooCommerce products into local database';

    public function handle(SyncProducts $sync): int
    {
        $perPage = (int) $this->option('per-page');

        if ((bool) $this->option('queued')) {
            SyncWooProductsJob::dispatch($perPage);
            $this->info('SyncWooProductsJob dispatched.');

            return self::SUCCESS;
        }

        $result = $sync->run($perPage);
        $this->info("Products synced: {$result['upserted']} records in {$result['duration']}ms");

        return self::SUCCESS;
    }
}
