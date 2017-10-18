<?php

namespace Tests\Feature;

use App\ProductManager\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_delete_a_product()
    {
        $product = factory(Product::class)->create();

        $this->json('DELETE', '/api/products/' . $product->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('products', $product->toArray());
    }

    /** @test */
    public function product_not_found()
    {
        $this->json('DELETE', '/api/products/111')
            ->assertStatus(404)
            ->assertJson([
                'messages' => 'Product not found.'
            ]);
    }
}
