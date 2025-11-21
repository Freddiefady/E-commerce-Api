<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

final class ProductController extends BaseController
{
    public function index(): JsonResponse
    {
        $products = Product::query()->latest()->paginate(15);

        return parent::successResponse(
            new ProductCollection($products),
            'Products retrieved successfully'
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::query()->create($request->validated());

        return parent::createdResponse(
            new ProductResource($product),
            'Product created successfully'
        );
    }

    public function show(Product $product): JsonResponse
    {
        return parent::successResponse(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return parent::successResponse(
            new ProductResource($product->fresh()),
            'Product updated successfully'
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return parent::successResponse(
            message: 'Product deleted successfully'
        );
    }
}
