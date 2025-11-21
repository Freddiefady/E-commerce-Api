<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
final class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'small_description' => $this->small_description,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => $this->price,
            'available_in_stock' => $this->available_in_stock,
            'stock_status' => $this->stock_status,
            'status' => $this->status,
            'available_for' => $this->available_for?->format('Y-m-d'),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
