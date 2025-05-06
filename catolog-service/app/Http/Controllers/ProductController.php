<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;


class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try{

            $products = $this->productService->getAll();

            return new DefaultResource(
                ProductResource::collection($products),
                true,
                200,
                'Products fetched successfully.',
            );
            

        }catch(\Exception $e){
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
       
    }

    public function show($id)
    {
        try{
            $product = $this->productService->getById($id);

            return new DefaultResource(
                new ProductResource($product),
                true,
                200,
                'Product fetched successfully.',
            );
          

        }catch(\Exception $e){
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
       
    }

    public function store(ProductRequest $request)
    {
        try{
            $product = $this->productService->create($request->validated());

            return new DefaultResource(
                ProductResource::collection($product),
                true,
                201,
                'Product created successfully.',
            );
            
        }catch(\Exception $e){
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
       
    }
    
}
