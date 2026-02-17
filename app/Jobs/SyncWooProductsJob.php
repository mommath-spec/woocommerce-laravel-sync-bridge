<?php

namespace App\Jobs;

use App\Services\WooCommerce\SyncProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncWooProductsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly int $perPage = 50)
    {
    }

    public function handle(SyncProducts $sync): void
    {
        $sync->run($this->perPage);
    }
}
