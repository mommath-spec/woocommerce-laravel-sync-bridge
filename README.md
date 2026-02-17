# WooCommerce → Laravel Sync Bridge

Synchronize WooCommerce products, orders and customers into a Laravel backend using the WooCommerce REST API.  
This project demonstrates a clean integration pattern between a WooCommerce store and a custom Laravel application.

## Features

- Simple WooCommerce API client service.
- Commands to sync products, orders and customers into local tables.
- Upsert logic based on WooCommerce IDs.
- Support for pagination to handle larger catalogs.
- Basic logging of sync runs.

## Requirements

- PHP 8.0+
- Laravel 10+
- WooCommerce store with REST API keys (v3).

## High-level architecture

WooCommerce REST API → Laravel WooClient → Sync Services → Local DB (products, orders, customers) → Optional dashboard.

## Planned structure

- `app/Services/WooCommerce/Client.php`
- `app/Services/WooCommerce/SyncProducts.php`
- `app/Services/WooCommerce/SyncOrders.php`
- `app/Services/WooCommerce/SyncCustomers.php`
- `app/Console/Commands/SyncWooProducts.php`
- `app/Console/Commands/SyncWooOrders.php`
- `app/Console/Commands/SyncWooCustomers.php`
- `config/woocommerce.php`
- `resources/views/woo-sync/index.blade.php`

## Roadmap

- [ ] Implement WooCommerce client with pagination and error handling.
- [ ] Add migrations for products, orders and customers tables.
- [ ] Implement Sync* services with upsert logic.
- [ ] Add artisan commands for manual sync.
- [ ] Add a minimal dashboard showing last sync status.

## License

MIT
