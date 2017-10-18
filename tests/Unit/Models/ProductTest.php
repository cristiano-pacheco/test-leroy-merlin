<?php

namespace Tests\Unit;

use App\ProductManager\Models\Category;
use App\ProductManager\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductTestTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_category()
    {
        $product = factory(Product::class)->create();
        $this->assertInstanceOf(Category::class, $product->category);
    }
}
