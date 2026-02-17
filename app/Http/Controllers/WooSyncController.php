<?php

namespace App\Http\Controllers;

use App\Models\SyncLog;
use App\Services\WooCommerce\SyncCustomers;
use App\Services\WooCommerce\SyncOrders;
use App\Services\WooCommerce\SyncProducts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WooSyncController extends Controller
{
    public function index(): View
    {
        if (! app()->environment('local', 'testing')) {
            abort(403);
        }

        $logs = SyncLog::query()
            ->latest()
            ->limit(30)
            ->get();

        return view('woo-sync.index', [
            'logs' => $logs,
            'stats' => [
                'products' => SyncLog::query()->where('type', 'products')->latest()->first(),
                'orders' => SyncLog::query()->where('type', 'orders')->latest()->first(),
                'customers' => SyncLog::query()->where('type', 'customers')->latest()->first(),
            ],
        ]);
    }

    public function run(string $type, Request $request, SyncProducts $products, SyncOrders $orders, SyncCustomers $customers): RedirectResponse
    {
        if (! app()->environment('local', 'testing')) {
            abort(403);
        }

        $perPage = max(1, min(100, (int) $request->input('per_page', config('woocommerce.per_page', 50))));

        $result = match ($type) {
            'products' => $products->run($perPage),
            'orders' => $orders->run($perPage),
            'customers' => $customers->run($perPage),
            default => null,
        };

        return back()->with('status', $result ? strtoupper($type) . ' synced (' . $result['upserted'] . ')' : 'Unknown sync type');
    }
}
