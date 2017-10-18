<?php

namespace Tests\Unit;

use App\ProductManager\Models\Category;
use App\ProductManager\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_product()
    {
        $category = factory(Category::class)->create();
        factory(Product::class)->create(['category_id' => $category->id]);
        $this->assertInstanceOf(Product::class, $category->products()->first());
    }
}
