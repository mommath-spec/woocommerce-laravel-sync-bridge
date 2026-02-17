<?php

namespace App\Console;

use App\Console\Commands\SyncWooCustomers;
use App\Console\Commands\SyncWooOrders;
use App\Console\Commands\SyncWooProducts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SyncWooProducts::class,
        SyncWooOrders::class,
        SyncWooCustomers::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('woo:sync-products --queued')->hourly();
        $schedule->command('woo:sync-orders --queued')->everyTwoHours();
        $schedule->command('woo:sync-customers --queued')->everyThreeHours();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
