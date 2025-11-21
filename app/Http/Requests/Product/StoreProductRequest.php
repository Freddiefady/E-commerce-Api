<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProductRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'small_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'status' => ['sometimes', 'boolean'],
            'sku' => ['required', 'string', 'unique:products,sku'],
            'available_for' => ['nullable', 'date'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'available_in_stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
