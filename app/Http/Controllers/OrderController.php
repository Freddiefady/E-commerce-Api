<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\StatusOrder;
use App\Exceptions\CartEmptyException;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

final class OrderController extends BaseController
{
    public function index(#[CurrentUser('api')] User $user): JsonResponse
    {
        $orders = $user->orders()->with('items')->latest()->paginate(15);

        return parent::successResponse(
            new OrderCollection($orders),
            'Orders retrieved successfully'
        );
    }

    /**
     * @throws Throwable
     */
    public function store(StoreOrderRequest $request, #[CurrentUser('api')] User $user): JsonResponse
    {
        $cart = $user->cart;

        throw_if(! $cart || $cart->isItemEmpty(), CartEmptyException::class);

        $cart->load('items.product');

        // Validate stock availability
        $stockErrors = [];
        foreach ($cart->items as $item) {
            if (! $item->product->isInStock()) {
                $stockErrors[] = "{$item->product->name} is out of stock";
            } elseif ($item->quantity > $item->product->available_in_stock) {
                $stockErrors[] = "{$item->product->name} has only {$item->product->available_in_stock} items available";
            }
        }

        if ($stockErrors !== []) {
            return parent::errorResponse('Stock validation failed', 422, $stockErrors);
        }

        try {
            $order = DB::transaction(function () use ($user, $cart, $request): Order {
                $total = $cart->getTotal();

                $order = Order::query()->create([
                    'user_id' => $user->id,
                    'address' => $request->validated('address'),
                    'phone' => $request->validated('phone'),
                    'total' => $total,
                    'status' => StatusOrder::PENDING->value,
                ]);

                foreach ($cart->items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->product->price,
                        'subtotal' => $item->quantity * $item->product->price,
                    ]);

                    // Decrease stock
                    $item->product->decrementStock($item->quantity);
                }

                // Clear the cart
                $cart->clear();

                return $order;
            });

            $order->load('items');

            return parent::createdResponse(
                new OrderResource($order),
                'Order placed successfully'
            );
        } catch (Exception $e) {
            return parent::errorResponse('Failed to create order', 500, $e->getMessage());
        }
    }

    public function show(Order $order, #[CurrentUser('api')] User $user): JsonResponse
    {
        if ($order->user_id !== $user->id) {
            return parent::errorResponse('Unauthorized', 403);
        }

        $order->load('items');

        return parent::successResponse(
            new OrderResource($order),
            'Order retrieved successfully'
        );
    }
}
