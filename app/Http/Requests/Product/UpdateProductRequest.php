<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

final class UpdateProductRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    /**
     * @return array<string, list<Unique|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'small_description' => ['sometimes', 'string', 'max:500'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', 'boolean'],
            'sku' => ['sometimes', 'string', Rule::unique('products')->ignore($this->route('product'))],
            'available_for' => ['nullable', 'date'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'available_in_stock' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
