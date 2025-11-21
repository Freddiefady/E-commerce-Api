<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\AvailableInStockException;
use App\Exceptions\CartNotFoundException;
use App\Exceptions\ItemNotFoundException;
use App\Exceptions\OutOfStockException;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Throwable;

final class CartController extends BaseController
{
    public function index(#[CurrentUser('api')] User $user): JsonResponse
    {
        $cart = $user->getOrCreateCart();
        $cart->load('items.product');

        return parent::successResponse(
            new CartResource($cart),
            'Cart retrieved successfully'
        );
    }

    /**
     * @throws OutOfStockException
     * @throws AvailableInStockException|Throwable
     */
    public function addItem(AddToCartRequest $request, #[CurrentUser('api')] User $user): JsonResponse
    {
        $cart = $user->getOrCreateCart();

        /** @var Product $product */
        $product = Product::query()->findOrFail($request->product_id);

        throw_unless($product->isInStock(), OutOfStockException::class);

        $existingItem = $cart->items()->where('product_id', $request->product_id)->first();

        $newQuantity = $existingItem
            ? $existingItem->quantity + $request->quantity
            : $request->quantity;

        if ($newQuantity > $product->available_in_stock) {
            throw new AvailableInStockException("Only $product->available_in_stock items available in stock");
        }

        if ($existingItem) {
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        $cart->load('items.product');

        return parent::successResponse(
            new CartResource($cart),
            'Item added to cart'
        );
    }

    /**
     * @throws CartNotFoundException
     * @throws ItemNotFoundException
     * @throws AvailableInStockException
     * @throws Throwable
     */
    public function updateItem(int $itemId, AddToCartRequest $request, #[CurrentUser('api')] User $user): JsonResponse
    {
        $cart = $user->cart;

        throw_unless($cart, CartNotFoundException::class);

        $item = $cart->items()->find($itemId);

        throw_unless($item, ItemNotFoundException::class);

        /** @var Product $product */
        $product = Product::query()->findOrFail($request->product_id);

        if ($request->quantity > $product->available_in_stock) {
            throw new AvailableInStockException("Only $product->available_in_stock items available in stock");
        }

        $item->update(['quantity' => $request->quantity]);

        $cart->load('items.product');

        return parent::successResponse(
            new CartResource($cart),
            'Cart item updated'
        );
    }

    /**
     * @throws CartNotFoundException
     * @throws ItemNotFoundException
     * @throws Throwable
     */
    public function removeItem(int $itemId, #[CurrentUser('api')] User $user): JsonResponse
    {
        $cart = $user->cart;

        throw_unless($cart, CartNotFoundException::class);

        $item = $cart->items()->find($itemId);

        throw_unless($item, ItemNotFoundException::class);

        $item->delete();

        $cart->load('items.product');

        return parent::successResponse(
            new CartResource($cart),
            'Item removed from cart'
        );
    }

    public function clear(#[CurrentUser('api')] User $user): JsonResponse
    {
        $cart = $user->cart;

        $cart?->clear();

        return parent::successResponse(
            message: 'Cart cleared'
        );
    }
}
