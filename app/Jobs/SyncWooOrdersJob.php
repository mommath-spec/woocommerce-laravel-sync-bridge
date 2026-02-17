<?php

namespace App\Jobs;

use App\Services\WooCommerce\SyncOrders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncWooOrdersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $perPage = 50)
    {
    }

    public function handle(SyncOrders $sync): void
    {
        $sync->run($this->perPage);
    }
}
