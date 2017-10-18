<?php

namespace App\ProductManager\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'free_shipping',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
