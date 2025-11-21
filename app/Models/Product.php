<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusOrder;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $small_description
 * @property string $description
 * @property bool $status
 * @property string $sku
 * @property Carbon|null $available_for
 * @property float $price
 * @property int $quantity
 * @property int $available_in_stock
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'small_description',
        'description',
        'status',
        'sku',
        'available_for',
        'price',
        'quantity',
        'available_in_stock',
    ];

    protected $appends = ['stock_status'];

    /**
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isInStock(): bool
    {
        return $this->available_in_stock > 0;
    }

    public function decrementStock(int $quantity): void
    {
        $this->decrement('available_in_stock', $quantity);
    }

    /**
     * @return Attribute<int|string, string>
     */
    protected function stockStatus(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->available_in_stock > 0 ? 'in_stock' : 'out_of_stock')->shouldCache();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusOrder::class,
            'price' => 'decimal:2',
            'available_for' => 'date',
        ];
    }
}
