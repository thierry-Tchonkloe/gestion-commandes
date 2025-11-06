<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('id'); // récupère l'id du produit en cours
        return [
            'name' => 'sometimes|required|string|max:255',
            'sku' => "sometimes|required|string|max:100|unique:products,sku,{$productId}",
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ];
    }
}
