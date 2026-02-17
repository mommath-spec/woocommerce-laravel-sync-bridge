<?php

namespace App\Services\WooCommerce;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;

class Client
{
    public function __construct(
        private readonly HttpFactory $http
    ) {
    }

    public function getProducts(int $page = 1, ?int $perPage = null): array
    {
        return $this->getCollection('products', $page, $perPage);
    }

    public function getOrders(int $page = 1, ?int $perPage = null): array
    {
        return $this->getCollection('orders', $page, $perPage);
    }

    public function getCustomers(int $page = 1, ?int $perPage = null): array
    {
        return $this->getCollection('customers', $page, $perPage);
    }

    private function getCollection(string $resource, int $page, ?int $perPage): array
    {
        $response = $this->request()
            ->get($this->endpoint($resource), [
                'page' => $page,
                'per_page' => $perPage ?? (int) config('woocommerce.per_page', 50),
            ])
            ->throw();

        $payload = $response->json();

        return is_array($payload) ? $payload : [];
    }

    private function request(): PendingRequest
    {
        return $this->http
            ->baseUrl($this->baseUrl())
            ->withBasicAuth(
                (string) config('woocommerce.consumer_key'),
                (string) config('woocommerce.consumer_secret')
            )
            ->timeout((int) config('woocommerce.timeout', 20))
            ->retry((int) config('woocommerce.retry_times', 3), 500);
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('woocommerce.store_url'), '/') . '/wp-json';
    }

    private function endpoint(string $resource): string
    {
        return '/' . trim((string) config('woocommerce.version', 'wc/v3'), '/') . '/' . ltrim($resource, '/');
    }
}
