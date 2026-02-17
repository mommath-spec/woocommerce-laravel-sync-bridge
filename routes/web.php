<?php

use App\Http\Controllers\WooSyncController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/woo-sync');
});

Route::middleware('web')->group(function (): void {
    Route::get('/woo-sync', [WooSyncController::class, 'index'])
        ->name('woo-sync.index');

    Route::post('/woo-sync/run/{type}', [WooSyncController::class, 'run'])
        ->whereIn('type', ['products', 'orders', 'customers'])
        ->name('woo-sync.run');
});
