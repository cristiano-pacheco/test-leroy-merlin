<?php


namespace App\ProductManager;

use Illuminate\Support\Facades\Validator;

class ProductValidator
{
    private $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function hasInvalidProduct()
    {
        if (empty($this->products)) {
            return true;
        }

        foreach ($this->products as $product) {
            $validator = Validator::make($product, $this->getRules());

            if ($validator->fails()) {
                return true;
            }
        }

        return false;
    }

    private function getRules()
    {
        return [
            'id' => 'required|integer',
            'category_id' => 'required|integer',
            'name' => 'required|max:255',
            'description' => 'required',
            'free_shipping' => 'required|in:0,1',
            'price' => 'required|numeric',
        ];
    }
}
