<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CartItem
 */
final class CartItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'subtotal' => $this->getSubtotal(),
            'product' => $this->whenLoaded('product', fn (): array => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'stock_status' => $this->product->stock_status,
                'available_in_stock' => $this->product->available_in_stock,
            ]),
        ];
    }
}
