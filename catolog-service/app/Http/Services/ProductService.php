<?php

namespace App\Http\Services;

use App\Models\Product;

class ProductService
{

    public function getAll()
    {
        return Product::all();
    }	

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function getById($id)
    {

        $product = Product::findOrFail($id);

        if (!$product->is_active) {
            throw new \Exception('Product not found or inactive');
        }

        return $product;
    }

}