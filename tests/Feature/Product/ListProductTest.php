<?php

namespace Tests\Feature;

use App\ProductManager\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ListProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_view_a_list_of_products()
    {
        factory(Product::class)->times(6)->create();

        $product1 = Product::with('category')->find(1)->toArray();
        $product2 = Product::with('category')->find(3)->toArray();
        $product3 = Product::with('category')->find(5)->toArray();

        $this->json('GET', '/api/products/')
            ->assertStatus(200)
            ->assertJsonFragment($product1)
            ->assertJsonFragment($product2)
            ->assertJsonFragment($product3);
    }

    /** @test */
    public function can_view_a_single_product()
    {
        $product = factory(Product::class)->create()->toArray();

        $this->json('GET', '/api/products/' . $product['id'])
            ->assertStatus(200)
            ->assertJson([
                'data' => $product
            ]);
    }

    /** @test */
    public function product_not_found()
    {
        $this->json('GET', '/api/products/111')
            ->assertStatus(404)
            ->assertJson([
                'messages' => 'Product not found.'
            ]);
    }
}
