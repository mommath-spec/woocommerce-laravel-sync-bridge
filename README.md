# Laravel WooCommerce Sync Bridge

Practical Laravel app for syncing WooCommerce data into a local relational model for custom backend workflows, BI, analytics, and integrations.

## What this project does

- Connects to WooCommerce REST API (`wc/v3`).
- Pulls paginated products, orders, and customers.
- Upserts data into local tables (`products`, `orders`, `customers`).
- Stores full `raw_payload` JSON for audit/replay.
- Logs every sync run in `sync_logs`.
- Exposes:
  - CLI commands (`woo:sync-products`, `woo:sync-orders`, `woo:sync-customers`)
  - queue jobs for heavier loads
  - scheduler definitions
  - read-only operations panel: `/woo-sync`

## Architecture (flow)

WooCommerce REST API -> `App\Services\WooCommerce\Client` -> `SyncProducts|SyncOrders|SyncCustomers` -> Local DB -> `sync_logs` + `/woo-sync` panel

## Repository structure

- `app/Services/WooCommerce/Client.php` - HTTP wrapper for Woo endpoints.
- `app/Services/WooCommerce/Sync*.php` - sync services with pagination + upsert.
- `app/Models/*` - Product, Order, Customer, SyncLog.
- `app/Console/Commands/*` - manual sync commands.
- `app/Jobs/*` - queueable sync jobs.
- `app/Console/Kernel.php` - scheduler setup.
- `config/woocommerce.php` - integration config.
- `routes/web.php` + `WooSyncController` + Blade panel.
- `database/migrations/*` - local schema.

## Setup

1. Install dependencies:

```bash
composer install
```

1. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

Set Woo credentials in `.env`:

```env
WOO_STORE_URL=https://your-store.example
WOO_CONSUMER_KEY=ck_xxx
WOO_CONSUMER_SECRET=cs_xxx
WOO_API_VERSION=wc/v3
```

1. Run migrations:

```bash
php artisan migrate
```

1. Optional queue setup:

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

## Running sync

Manual sync examples:

```bash
php artisan woo:sync-products
php artisan woo:sync-orders
php artisan woo:sync-customers
```

Queued variant:

```bash
php artisan woo:sync-products --queued
```

## Scheduler

Defined in `app/Console/Kernel.php`:

- products hourly,
- orders every 2 hours,
- customers every 3 hours.

Set cron in production:

```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

## Web panel

- Route: `/woo-sync`
- Purpose: operational visibility over recent syncs.
- Includes manual trigger buttons (local/testing guarded).

## Tests

- `tests/Feature/SyncProductsTest.php` demonstrates fake Woo client -> upsert assertions.

## Docker (optional)

`docker-compose.yml` includes MySQL and a lightweight PHP service for quick local bootstrapping.

## Why this repo matters in portfolio

- Real-world WooCommerce -> Laravel integration scenario.
- Clean separation: client layer, sync services, jobs, commands.
- Focus on data integrity (`raw_payload`) and operational logging (`sync_logs`).
