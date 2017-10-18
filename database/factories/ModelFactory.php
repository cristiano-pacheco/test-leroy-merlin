<?php

use App\ProductManager\Models\Category;
use App\ProductManager\Models\File;
use App\ProductManager\Models\Product;
use Faker\Generator;

$factory->define(Category::class, function (Generator $faker) {
    return [
        'name' => $faker->sentence,
    ];
});

$factory->define(Product::class, function (Generator $faker) {
    return [
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'name' => $faker->sentence,
        'description' => $faker->sentence,
        'free_shipping' => $faker->randomElement([0, 1]),
        'price' => $faker->randomFloat(2)
    ];
});

$factory->define(File::class, function (Generator $faker) {
    return [
        'original_name' => 'file.xlsx',
        'stored_name' => 'files/file.xlsx',
    ];
});

$factory->state(File::class, 'pending_status', function () {
    return [
        'processing_status' => File::STATUS_PENDING,
    ];
});

$factory->state(File::class, 'processed_status', function () {
    return [
        'processing_status' => File::STATUS_PROCESSED,
    ];
});

$factory->state(File::class, 'failed_status', function () {
    return [
        'processing_status' => File::STATUS_FAILED,
    ];
});
