<?php

namespace App\Console\Commands;

use App\Jobs\SyncWooCustomersJob;
use App\Services\WooCommerce\SyncCustomers;
use Illuminate\Console\Command;

class SyncWooCustomers extends Command
{
    protected $signature = 'woo:sync-customers {--queued : Dispatch as queue job} {--per-page=50}';

    protected $description = 'Synchronize WooCommerce customers into local database';

    public function handle(SyncCustomers $sync): int
    {
        $perPage = (int) $this->option('per-page');

        if ((bool) $this->option('queued')) {
            SyncWooCustomersJob::dispatch($perPage);
            $this->info('SyncWooCustomersJob dispatched.');

            return self::SUCCESS;
        }

        $result = $sync->run($perPage);
        $this->info("Customers synced: {$result['upserted']} records in {$result['duration']}ms");

        return self::SUCCESS;
    }
}
