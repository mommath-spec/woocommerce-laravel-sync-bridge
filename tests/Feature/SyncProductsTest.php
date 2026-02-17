<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\WooCommerce\Client;
use App\Services\WooCommerce\SyncProducts;
use Mockery;
use Tests\TestCase;

class SyncProductsTest extends TestCase
{
    public function test_sync_products_upserts_records(): void
    {
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('getProducts')
            ->once()
            ->with(1, 2)
            ->andReturn([
                ['id' => 101, 'name' => 'Alpha', 'sku' => 'A-1', 'price' => '12.00', 'status' => 'publish'],
                ['id' => 102, 'name' => 'Beta', 'sku' => 'B-1', 'price' => '22.00', 'status' => 'publish'],
            ]);
        $client->shouldReceive('getProducts')
            ->once()
            ->with(2, 2)
            ->andReturn([]);

        $service = new SyncProducts($client);
        $result = $service->run(2);

        $this->assertSame(2, $result['upserted']);
        $this->assertDatabaseHas('products', ['woo_id' => 101, 'name' => 'Alpha']);
        $this->assertDatabaseHas('products', ['woo_id' => 102, 'name' => 'Beta']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
