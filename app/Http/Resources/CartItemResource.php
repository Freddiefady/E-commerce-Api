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
            'product_name' => $this->whenLoaded('product', fn () => $this->product->name),
            'price' => $this->whenLoaded('product', fn () => $this->product->price),
            'quantity' => $this->quantity,
            'subtotal' => $this->getSubtotal(),
            'stock_status' => $this->whenLoaded('product', fn () => $this->product->stock_status),
        ];
    }
}
