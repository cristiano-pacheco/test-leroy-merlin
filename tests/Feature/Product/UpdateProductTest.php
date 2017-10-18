<?php

namespace Tests\Feature;

use App\ProductManager\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_update_a_product()
    {
        $product = factory(Product::class)->create();
        $productForUpdate = factory(Product::class)->make();

        $payload = $productForUpdate->toArray();

        $this->json('PUT', '/api/products/' . $product->id, $payload)
            ->assertStatus(200)
            ->assertJson([
                'data' => $payload
            ]);

        $this->assertDatabaseHas('products', $payload);
    }

    /** @test */
    public function update_a_product_error_validation()
    {
        $product = factory(Product::class)->create();

        $this->json('PUT', '/api/products/' . $product->id, [])
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_update_a_product_not_found()
    {
        $product = factory(Product::class)->create()->toArray();

        unset($product['id']);

        $this->json('PUT', '/api/products/123456', $product)
            ->assertStatus(404)
            ->assertJson([
                'messages' => 'Product not found.'
            ]);
    }
}
